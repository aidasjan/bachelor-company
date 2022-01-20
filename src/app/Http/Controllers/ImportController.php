<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Category;
use App\Subcategory;
use App\Product;
use App\Imports\CategoriesImport;
use App\Imports\SubcategoriesImport;
use App\Imports\ProductsImport;
use App\Services\FileService;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUploadForm($type)
    {
        if (auth()->user()->isAdmin() && $this->isImportTypeSupported($type)) {
            return view('pages.admin.import.upload')->with('type', $type);
        }
        else abort(404);
    }

    public function importFromFile(Request $request, $type)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateImportRequest($request);
            $import = $this->getImportByType($type);
            if ($import && $request->hasFile('import_file')) {
                $file_service = new FileService;
                $file = $file_service->uploadFile($request->file('import_file'), 'import_file', null, 'public');
                $uploaded_file_path = $file_service->getValidatedFilePath($file->id);
                Excel::import($import, $uploaded_file_path);
                $file_service->deleteFile($file->id);
                return view('pages.admin.import.results')->with('import_results', $import->getImportResults());
            } 
            else abort(404);
        }
        else abort(404);
    }

    private function validateImportRequest(Request $request)
    {
        $allowed_mimes = config('custom.files.import_file.allowed_file_types');
        $max_file_size = config('custom.files.import_file.max_file_size');
        $request->validate([
            'import_file' => [
                'required',
                'file',
                'mimes:'.$allowed_mimes,
                'max:'.$max_file_size
            ],
        ]);
    }

    private function isImportTypeSupported($type) 
    {
        $supported_types = ['products', 'categories', 'subcategories'];
        return in_array($type, $supported_types);
    }

    private function getImportByType($type) 
    {
        switch ($type) {
            case 'categories': return new CategoriesImport;
            case 'subcategories': return new SubcategoriesImport;
            case 'products': return new ProductsImport;
            default: return null;
        }
    }
}
