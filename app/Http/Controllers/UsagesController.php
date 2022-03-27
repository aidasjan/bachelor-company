<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UsageService;

class UsagesController extends Controller
{
    public function __construct(UsageService $usageService)
    {
        $this->middleware('auth');
        $this->usageService = $usageService;
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $usages = $this->usageService->all();
            return view('pages.usages.index')->with('usages', $usages);
        } else abort(404);
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.usages.create');
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $this->usageService->store($request);
            return redirect('/usages');
        } else abort(404);
    }

    private function validateStoreRequest($request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $usage = $this->usageService->find($id);
            if ($usage === null) abort(404);
            return view('pages.usages.edit')->with('usage', $usage);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $usage = $this->usageService->update($request, $id);
            if ($usage === null) {
                abort(404);
            }
            return redirect('/usages');
        } else abort(404);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $this->usageService->destroy($id);
            return redirect('/usages');
        } else abort(404);
    }
}
