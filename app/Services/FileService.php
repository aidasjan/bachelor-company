<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\File;

class FileService
{
    public function getValidatedFilePath($fileId, $disk = 'public') {
        $file = File::find($fileId);
        if ($file == null) {
            return null;
        }
        $filePath = $this->getFilePathInDisk($file);
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->path($filePath);
        } else {
            return null;
        }
    }

    public function deleteFile($fileId, $disk = 'public') {
        $file = File::find($fileId);
        if ($file === null) {
            return null;
        }
        Storage::disk($disk)->delete($this->getFilePathInDisk($file));
        $file->delete();
    }

    public function uploadFile($file, $type, $name = null, $disk = 'public') {
        $fileRecord = new File;
        $fileRecord->name = $name ?? $file->getClientOriginalName();
        $fileRecord->type = $type;
        $fileRecord->file_name = $this->generateRandomFileName();
        $fileRecord->file_extension = $file->extension();
        $fileRecord->file_mime_type = $file->getMimeType();
        $fileRecord->save();

        $file->storeAs('uploads', $this->getFileFullName($fileRecord), $disk);

        return $fileRecord;
    }

    private function getFilePathInDisk($file)
    {
        return 'uploads/'.$this->getFileFullName($file);
    }

    private function getFileFullName($file)
    {
        return $file->file_name.'.'.strtolower($file->file_extension);
    }

    private function generateRandomFileName()
    {
        $randFileNameLength = 64;
        $randFileName = "";
        for ($i = 0; $i < $randFileNameLength; $i++) {
            $randFileName .= strval(random_int(0, 9));
        }
        $randFileName .= strval(time());
        return $randFileName;
    }
}
