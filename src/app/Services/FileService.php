<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\File;

class FileService
{
    public function getValidatedFilePath($file_id, $disk = 'public') {
        $file = File::find($file_id);
        if ($file == null) {
            return null;
        }
        $file_path = $this->getFilePathInDisk($file);
        if (Storage::disk($disk)->exists($file_path)) {
            return Storage::disk($disk)->path($file_path);
        } else {
            return null;
        }
    }

    public function deleteFile($file_id, $disk = 'public') {
        $file = File::find($file_id);
        if ($file === null) {
            return null;
        }
        Storage::disk($disk)->delete($this->getFilePathInDisk($file));
        $file->delete();
    }

    public function uploadFile($file, $type, $name = null, $disk = 'public') {
        $file_record = new File;
        $file_record->name = $name ?? $file->getClientOriginalName();
        $file_record->type = $type;
        $file_record->file_name = $this->generateRandomFileName();
        $file_record->file_extension = $file->extension();
        $file_record->file_mime_type = $file->getMimeType();
        $file_record->save();

        $file->storeAs('uploads', $this->getFileFullName($file_record), $disk);

        return $file_record;
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
        $rand_file_name_length = 64;
        $rand_file_name = "";
        for ($i = 0; $i < $rand_file_name_length; $i++) {
            $rand_file_name .= strval(random_int(0, 9));
        }
        $rand_file_name .= strval(time());
        return $rand_file_name;
    }
}
