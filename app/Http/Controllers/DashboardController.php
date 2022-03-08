<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{

    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        if (auth()->user()->isNewClient()) return redirect('/password');

        if (auth()->user()->isClient()) {
            $data = $this->dashboardService->getClientDashboardData();
            return view('pages.client.dashboard')->with($data);
        } else if (auth()->user()->isAdmin()) {
            $submittedOrders = $this->dashboardService->getAdminDashboardData();
            return view('pages.admin.dashboard')->with('submittedOrders', $submittedOrders);
        } else abort(404);
    }
}
