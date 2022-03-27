<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;

class ImportController extends Controller
{
    public function __construct(ImportService $importService)
    {
        $this->middleware('auth');
        $this->importService = $importService;
    }

    public function showUploadForm($type)
    {
        if (auth()->user()->isAdmin() && $this->importService->isImportTypeSupported($type)) {
            return view('pages.admin.import.upload')->with('type', $type);
        }
        else abort(404);
    }

    public function importFromFile(Request $request, $type)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateImportRequest($request);
            $importResults = $this->importService->importFromFile($request->file('import_file'), $type);
            if ($importResults === null) {
                abort(404);
            }
            return view('pages.admin.import.results')->with('importResults', $importResults);
        }
        else abort(404);
    }

    private function validateImportRequest(Request $request)
    {
        $allowedMimes = config('custom.files.import_file.allowed_file_types');
        $maxFileSize = config('custom.files.import_file.max_file_size');
        $request->validate([
            'import_file' => [
                'required',
                'file',
                'mimes:'.$allowedMimes,
                'max:'.$maxFileSize
            ],
        ]);
    }
}
