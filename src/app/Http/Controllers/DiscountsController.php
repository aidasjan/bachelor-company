<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;
use App\User;
use App\Category;
use App\Services\DiscountService;

class DiscountsController extends Controller
{
    public function __construct(DiscountService $discountService)
    {
        $this->middleware('auth');
        $this->discountService = $discountService;
    }

    public function index()
    {
        if (auth()->user()->isClient()) {
            $discounts = $this->discountService->getUserDiscounts(null);
            return view('pages.discounts.index')->with('discounts', $discounts);
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $result = $this->discountService->store($request);
            if ($result == null) abort(404);
            return redirect('/users');
        } else abort(404);
    }

    private function validateStoreRequest(Request $request) {
        $this->validate($request, [
            'discountUser' => 'required'
        ]);
    }

    public function storeAll(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreAllRequest($request);
            $result = $this->discountService->storeAll($request);
            if ($result == null) abort(404);
            return redirect('/users');
        } else abort(404);
    }

    private function validateStoreAllRequest(Request $request) {
        $this->validate($request, [
            'discountUser' => 'required',
            'discount' => 'required'
        ]);
    }

    public function edit($userId)
    {
        if (auth()->user()->isAdmin()) {
            $data = $this->discountService->getCategoriesWithUserDiscounts($userId);
            if ($data == null) abort(404);
            return view('pages.discounts.edit')->with($data);
        } else abort(404);
    }
}
