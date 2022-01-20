<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use App\Services\BackupService;

class BackupsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['scheduledBackup']]);
    }

    public function scheduledBackup($token)
    {
        if ($token == null || $token !== config('custom.backup.token')) { abort(404); return; }
        $backup_service = new BackupService;
        $backup_service->doScheduledBackup();
        abort(404);
    }
}
