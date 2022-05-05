<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReorderingService;

class ReorderController extends Controller
{
    
    public function __construct(ReorderingService $reorderingService)
    {
        $this->middleware('auth');
        $this->reorderingService = $reorderingService;
    }

    public function index(Request $request, $type, $parentId = null)
    {
        if (auth()->user()->isAdmin()) {
            $items = $this->reorderingService->getItemsToReorder($type, $parentId);
            if ($items == null || $items->count() == 0) { abort(404); }
            return view('pages.admin.reorder.index')->with([
                'items' => $items,
                'type' => $type,
                'parentId' => $parentId,
                'redirectUrl' => $request->input('redirectUrl'),
            ]);
        }
        else abort(404);
    }

    public function reorder(Request $request, $type, $parentId = null)
    {
        if (auth()->user()->isAdmin()) {
            $this->reorderingService->reorderItems($request, $type, $parentId);
            $redirectUrl = $request->input('redirect_url');
            if ($redirectUrl) {
                return redirect($redirectUrl);
            }
            return redirect()->back();
        }
        else abort(404);
    }

    public function reorderRoot(Request $request, $type)
    {
        if (auth()->user()->isAdmin()) {
            $this->reorderingService->reorderItems($request, $type);
            return redirect('/');
        }
        else abort(404);
    }
}
