<?php

namespace App\Services;

use App\Discount;
use Illuminate\Http\Request;

class DiscountService
{
    public function __construct(UserService $userService, CategoryService $categoryService)
    {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    public function getUserDiscounts($count)
    {
        if ($count === null) {
            return Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->get();
        }
        return Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->take($count)->get();
    }

    public function store(Request $request)
    {
        $user = $this->userService->find($request->input('discountUser'));
        if ($user === null || !($user->isClient() || $user->isNewClient())) {
            return null;
        }

        $userDiscounts = $user->getAllDiscounts();
        $inputs = $request->all();

        foreach ($inputs as $key => $value) {
            $category = $this->categoryService->find($key);
            if ($category !== null) {
                if (!(is_numeric($value) || $value == '')) {
                    return null;
                }
                $discount = $userDiscounts->where('category_id', $key)->first();
                if ($discount !== null) {
                    if ($value > 0) {
                        $discount->discount = $value;
                        $discount->save();
                    } else {
                        $discount->delete();
                    }
                } else {
                    if ($value > 0) {
                        $discount = new Discount;
                        $discount->user_id = $user->id;
                        $discount->category_id = $key;
                        $discount->discount = $value;
                        $discount->save();
                    }
                }
            }
        }

        return $user;
    }

    public function storeAll(Request $request)
    {
        $user = $this->userService->find($request->input('discountUser'));
        if ($user === null || !($user->isClient() || $user->isNewClient())) {
            return null;
        }

        $userDiscounts = $user->getAllDiscounts();

        $discountValue = $request->input('discount');
        if (!(is_numeric($discountValue) || $discountValue == '')) {
            return null;
        }

        if ($discountValue > 0) {
            $categories = $this->categoryService->all();
            foreach ($categories as $category) {
                if ($userDiscounts->where('category_id', $category->id)->first() === null) {
                    $discount = new Discount;
                    $discount->user_id = $user->id;
                    $discount->category_id = $category->id;
                    $discount->discount = $discountValue;
                    $discount->save();
                }
            }
        }

        return $user;
    }

    public function getCategoriesWithUserDiscounts($userId)
    {
        $user = $this->userService->find($userId);
        if ($user === null || !($user->isClient() || $user->isNewClient())) {
            return null;
        }

        $categories = $this->categoryService->all();
        foreach ($categories as $category) {
            $category->discount = $user->getDiscount($category);
        }

        $data = array(
            'user' => $user,
            'categories' => $categories
        );

        return $data;
    }
}
