<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\FileService;

class FilesController extends Controller
{

    public function __construct(FileService $fileService)
    {
        $this->middleware('auth', ['except' => ['showDocument', 'showImage']]);
        $this->fileService = $fileService;
    }

    public function showDocument($id)
    {
        $path = $this->fileService->getValidatedFilePath($id);
        if ($path) {
            $file = File::find($id);
            return response()->download($path, $file->getNameWithExtension());
        } else abort(404);
    }

    public function showImage($id)
    {
        $path = $this->fileService->getValidatedFilePath($id);
        if ($path) {
            $file = File::find($id);
            if ($file->isImage()) {
                return response()->file($path);
            } else abort(404);
        } else abort(404);
    }
}
