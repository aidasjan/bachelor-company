<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReorderingService;

class ReorderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($type, $parent_id = null)
    {
        if (auth()->user()->isAdmin()) {
            $reordering_service = new ReorderingService;
            $items = $reordering_service->getItemsToReorder($type, $parent_id);
            if ($items == null || $items->count() == 0) { abort(404); }
            return view('pages.admin.reorder.index')->with([
                'items' => $items,
                'type' => $type,
                'parent_id' => $parent_id
            ]);
        }
        else abort(404);
    }

    public function reorder(Request $request, $type, $parent_id = null)
    {
        if (auth()->user()->isAdmin()) {
            $reordering_service = new ReorderingService;
            $reordering_service->reorderItems($request, $type, $parent_id);
            return redirect()->back();
        }
        else abort(404);
    }

    public function reorderRoot(Request $request, $type)
    {
        if (auth()->user()->isAdmin()) {
            $reordering_service = new ReorderingService;
            $reordering_service->reorderItems($request, $type);
            return redirect()->back();
        }
        else abort(404);
    }
}
