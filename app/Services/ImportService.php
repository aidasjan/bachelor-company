<?php

namespace App\Services;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoriesImport;
use App\Imports\ProductsImport;
use App\Services\FileService;

class ImportService
{
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function importFromFile($requestFile, $type)
    {
        $import = $this->getImportByType($type);
        if ($import) {
            $file_service = new FileService;
            $file = $file_service->uploadFile($requestFile, 'import_file', null, 'public');
            $uploaded_file_path = $file_service->getValidatedFilePath($file->id);
            Excel::import($import, $uploaded_file_path);
            $file_service->deleteFile($file->id);
            return $import->getImportResults();
        }
    }

    public function isImportTypeSupported($type)
    {
        return in_array($type, ['products', 'categories']);
    }

    private function getImportByType($type)
    {
        switch ($type) {
            case 'categories':
                return new CategoriesImport;
            case 'products':
                return new ProductsImport;
            default:
                return null;
        }
    }
}
