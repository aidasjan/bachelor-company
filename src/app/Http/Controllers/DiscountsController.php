<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;
use App\User;
use App\Category;

class DiscountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->isClient()) {
            $discounts = Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->get();
            return view('pages.discounts.index')->with('discounts', $discounts);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {

            $this->validate($request, [
                'dis_user' => 'required'
            ]);

            // Get and validate user
            $user = User::find($request->input('dis_user'));
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            // Get client's discounts
            $userDiscounts = $user->getAllDiscounts();

            $inputs = $request->all();
            foreach ($inputs as $key => $value) {
                // Validate inputs
                $category = Category::find($key);
                if ($category !== null) {

                    if (!(is_numeric($value) || $value == '')) abort(404); // If value is not valid

                    // If discount is already set
                    if ($userDiscounts->where('category_id', $key)->first() !== null) {
                        $discount = $userDiscounts->where('category_id', $key)->first();
                        if ($value > 0) {
                            $discount->discount = $value;
                            $discount->save();
                        } else {
                            $discount->delete();
                        }
                    }
                    // If discount is new
                    else {
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

            return redirect('/users');
        } else abort(404);
    }

    public function storeAll(Request $request)
    {
        if (auth()->user()->isAdmin()) {

            $this->validate($request, [
                'dis_user' => 'required',
                'discount' => 'required'
            ]);

            // Get and validate user
            $userID = $request->input('dis_user');
            $user = User::find($userID);
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            // Get client's discounts
            $user_discounts = $user->getAllDiscounts();

            $discount_value = $request->input('discount');
            if (!(is_numeric($discount_value) || $discount_value == '')) abort(404);

            if ($discount_value > 0) {
                $categories = Category::all();
                foreach ($categories as $category) {
                    if ($user_discounts->where('category_id', $category->id)->first() === null) {
                        $discount = new Discount;
                        $discount->user_id = $user->id;
                        $discount->category_id = $category->id;
                        $discount->discount = $discount_value;
                        $discount->save();
                    }
                }
            }

            return redirect('/discounts' . '/' . $user->id . '/edit');
        } else abort(404);
    }

    public function show($id)
    {
        //
    }

    public function edit($userID)
    {
        if (auth()->user()->isAdmin()) {
            $user = User::find($userID);
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            $categories = Category::all();

            $data = array(
                'user' => $user,
                'categories' => $categories
            );

            foreach ($data['categories'] as $categories) {
                $categories->discount = $user->getDiscount($categories);
            }

            return view('pages.discounts.edit')->with($data);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
