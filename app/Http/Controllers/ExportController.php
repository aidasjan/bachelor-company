<?php

namespace App\Http\Controllers;

use App\Services\ExportService;

class ExportController extends Controller
{
    public function __construct(ExportService $exportService)
    {
        $this->middleware('auth');
        $this->exportService = $exportService;
    }

    public function export($type)
    {
        if (auth()->user()->isAdmin()) {
            return $this->exportService->export($type);
        } else abort(404);
    }
}
