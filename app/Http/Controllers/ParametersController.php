<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ParameterService;

class ParametersController extends Controller
{
    public function __construct(ParameterService $parameterService)
    {
        $this->middleware('auth');
        $this->parameterService = $parameterService;
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $parameters = $this->parameterService->all();
            return view('pages.parameters.index')->with('parameters', $parameters);
        } else abort(404);
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.parameters.create');
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $this->parameterService->store($request);
            return redirect('/parameters');
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
            $parameter = $this->parameterService->find($id);
            if ($parameter === null) abort(404);
            return view('pages.parameters.edit')->with('parameter', $parameter);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $parameter = $this->parameterService->update($request, $id);
            if ($parameter === null) {
                abort(404);
            }
            return redirect('/parameters');
        } else abort(404);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $this->parameterService->destroy($id);
            return redirect('/parameters');
        } else abort(404);
    }
}
