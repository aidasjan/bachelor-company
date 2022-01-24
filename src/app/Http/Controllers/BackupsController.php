<?php

namespace App\Http\Controllers;

use App\Services\BackupService;

class BackupsController extends Controller
{

    public function __construct(BackupService $backupService)
    {
        $this->middleware('auth', ['except' => ['scheduledBackup']]);
        $this->backupService = $backupService;
    }

    public function scheduledBackup($token)
    {
        if ($token == null || $token !== config('custom.backup.token')) abort(404);
        $this->backupService->doScheduledBackup();
    }
}
