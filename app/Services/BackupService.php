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
        $directory_name = date("Y-m-d_H-i-s");
        $this->doDatabaseBackup($directory_name);
        $this->doPublicFilesBackup($directory_name);
    }

    private function deleteOldBackups()
    {
        $directory_names = Storage::disk('backup')->directories('/');
        $directory_times = collect($directory_names)->map(function ($directory_name) { 
            return [
                'name' => $directory_name, 
                'time' => Storage::disk('backup')->lastModified($directory_name)
            ];
        })->sortByDesc('time');
        $old_backup_directories = $directory_times->skip(config('custom.backup.backups_to_keep'));
        foreach ($old_backup_directories as $old_backup_directory) {
            Storage::disk('backup')->deleteDirectory($old_backup_directory['name']);
        }
    }

    private function needScheduledBackup()
    {
        $directories = collect(Storage::disk('backup')->directories('/'));
        if ($directories->count() === 0) { return true; }
        $directory_times = $directories->map(function ($directory) { return Storage::disk('backup')->lastModified($directory); });
        $most_recent_time = $directory_times->max();
        $current_time = time();
        return $most_recent_time < ($current_time - config('custom.backup.interval_days') * 24 * 60 * 60);
    }

    private function doPublicFilesBackup($directory_name)
    {
        $file_names = Storage::disk('public')->allFiles('/');
        foreach ($file_names as $file_name) {
            Storage::disk('backup')->put($directory_name.'/'.'files'.'/'.$file_name, Storage::disk('public')->get($file_name));
        }
    }

    private function doDatabaseBackup($directory_name)
    {
        $sql_content = $this->generateBackupSql();
        Storage::disk('backup')->put($directory_name.'/'.'db-backup.sql', $sql_content);
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
        $sql_content = "";
        foreach ($models as $model) {
            try {
                $sql_content .= $this->generateSqlContent($model::all()->toArray(), (new $model)->getTable())."\n";
            } catch (\Throwable $e) {
                continue;
            }
        }
        return $sql_content;
    }

    private function generateSqlContentForTables($tables)
    {
        $sql_content = "";
        foreach ($tables as $table) {
            try {
                $records = DB::table($table)->select('*')->get();
                $sql_content .= $this->generateSqlContent($records, $table)."\n";
            } catch (\Throwable $e) {
                continue;
            }
        }
        return $sql_content;
    }

    private function generateSqlContent($records, $table_name)
    {
        $sql_content = "";
        foreach ($records as $record) {
            $record = is_array($record) ? $record : (array) $record;
            foreach ($record as $key=>$value) { $record[$key] = addslashes($value); }
            $columns = "(`".implode('`,`', array_keys($record))."`)";
            $values = "('".implode("','", $record)."')";
            $sql_content .= "INSERT INTO $table_name $columns VALUES $values; \n";
        }
        return $sql_content;
    }
}
