<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use App\Services\FileService;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showDocument', 'showImage']]);
    }

    public function showDocument($id)
    {
        $file_service = new FileService;
        $path = $file_service->getValidatedFilePath($id);
        if ($path) {
            $file = File::find($id);
            return response()->download($path, $file->getNameWithExtension());
        } else abort(404); 
    }

    public function showImage($id)
    {
        $file_service = new FileService;
        $path = $file_service->getValidatedFilePath($id);
        if ($path) {
            $file = File::find($id);
            if ($file->isImage()) {
                return response()->file($path);
            } else { abort(404); }
        } else abort(404); 
    }
}
