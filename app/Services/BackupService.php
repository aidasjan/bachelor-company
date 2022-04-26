<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupService
{

    public function doScheduledBackup() 
    {
        if ($this->needScheduledBackup()) {
            $this->doFullBackup();
            $this->deleteOldBackups();
        }
    }

    private function doFullBackup() 
    {
        $directoryName = date("Y-m-d_H-i-s");
        $this->doDatabaseBackup($directoryName);
        $this->doPublicFilesBackup($directoryName);
    }

    private function deleteOldBackups()
    {
        $directoryNames = Storage::disk('backup')->directories('/');
        $directoryTimes = collect($directoryNames)->map(function ($directoryName) { 
            return [
                'name' => $directoryName, 
                'time' => Storage::disk('backup')->lastModified($directoryName)
            ];
        })->sortByDesc('time');
        $oldDackupDirectories = $directoryTimes->skip(config('custom.backup.backups_to_keep'));
        foreach ($oldDackupDirectories as $oldBackupDirectory) {
            Storage::disk('backup')->deleteDirectory($oldBackupDirectory['name']);
        }
    }

    private function needScheduledBackup()
    {
        $directories = collect(Storage::disk('backup')->directories('/'));
        if ($directories->count() === 0) { return true; }
        $directoryTimes = $directories->map(function ($directory) { return Storage::disk('backup')->lastModified($directory); });
        $mostRecentTime = $directoryTimes->max();
        $currentTime = time();
        return $mostRecentTime < ($currentTime - config('custom.backup.interval_days') * 24 * 60 * 60);
    }

    private function doPublicFilesBackup($directoryName)
    {
        $fileNames = Storage::disk('public')->allFiles('/');
        foreach ($fileNames as $fileName) {
            Storage::disk('backup')->put($directoryName.'/'.'files'.'/'.$fileName, Storage::disk('public')->get($fileName));
        }
    }

    private function doDatabaseBackup($directoryName)
    {
        $sqlContent = $this->generateBackupSql();
        Storage::disk('backup')->put($directoryName.'/'.'db-backup.sql', $sqlContent);
    }

    private function generateBackupSql()
    {
        $models = config('custom.backup.models');
        $relationship_tables = config('custom.backup.tables');

        $sql_content = "";
        $sql_content .= $this->generateSqlContentForModels($models);
        $sql_content .= $this->generateSqlContentForTables($relationship_tables);
        return $sql_content;
    }

    private function generateSqlContentForModels($models)
    {
        $sqlContent = "";
        foreach ($models as $model) {
            try {
                $sqlContent .= $this->generateSqlContent($model::all()->toArray(), (new $model)->getTable())."\n";
            } catch (\Throwable $e) {
                continue;
            }
        }
        return $sqlContent;
    }

    private function generateSqlContentForTables($tables)
    {
        $sqlContent = "";
        foreach ($tables as $table) {
            try {
                $records = DB::table($table)->select('*')->get();
                $sqlContent .= $this->generateSqlContent($records, $table)."\n";
            } catch (\Throwable $e) {
                continue;
            }
        }
        return $sqlContent;
    }

    private function generateSqlContent($records, $tableName)
    {
        $sqlContent = "";
        foreach ($records as $record) {
            $record = is_array($record) ? $record : (array) $record;
            foreach ($record as $key=>$value) { $record[$key] = addslashes($value); }
            $columns = "(`".implode('`,`', array_keys($record))."`)";
            $values = "('".implode("','", $record)."')";
            $sqlContent .= "INSERT INTO $tableName $columns VALUES $values; \n";
        }
        return $sqlContent;
    }
}
