<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;
use App\User;
use App\Subcategory;

class DiscountsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isClient()){
            $discounts = Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->get();
            return view('pages.discounts.index')->with('discounts', $discounts);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()){

            $this->validate($request,[
                'dis_user'=>'required'
            ]);

            // Get and validate user
            $userID = $request->input('dis_user');
            $user = User::find($userID);
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            // Get client's discounts
            $user_discounts = $user->getAllDiscounts();

            $inputs = $request->all();
            foreach ($inputs as $key=>$value){
                // Validate inputs
                $subcategory = Subcategory::find($key);
                if ($subcategory !== null){

                    if (!(is_numeric($value) || $value=='')) abort(404); // If value is not valid

                    // If discount is already set
                    if ($user_discounts->where('subcategory_id', $key)->first() !== null){
                        $discount = $user_discounts->where('subcategory_id', $key)->first();
                        if ($value > 0){
                            $discount->discount = $value;
                            $discount->save();
                        }
                        else{
                            $discount->delete();
                        }
                    }
                    // If discount is new
                    else{
                        if ($value > 0){
                            $discount = new Discount;
                            $discount->user_id = $user->id;
                            $discount->subcategory_id = $key;
                            $discount->discount = $value;
                            $discount->save();
                        }
                    }
                }
            }

            return redirect('/users');
        }
        else abort(404);
    }

    /**
     * Store discount for all subcategories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAll(Request $request)
    {
        if (auth()->user()->isAdmin()){

            $this->validate($request,[
                'dis_user'=>'required',
                'discount'=>'required'
            ]);

            // Get and validate user
            $userID = $request->input('dis_user');
            $user = User::find($userID);
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            // Get client's discounts
            $user_discounts = $user->getAllDiscounts();

            $discount_value = $request->input('discount');
            if (!(is_numeric($discount_value) || $discount_value == '')) abort(404);
            
            if ($discount_value > 0){
                $subcategories = Subcategory::all();
                foreach ($subcategories as $subcategory){
                    if ($user_discounts->where('subcategory_id', $subcategory->id)->first() === null){
                        $discount = new Discount;
                        $discount->user_id = $user->id;
                        $discount->subcategory_id = $subcategory->id;
                        $discount->discount = $discount_value;
                        $discount->save();
                    }
                }
            }

            return redirect('/discounts'.'/'.$user->id.'/edit');
        }
        else abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($userID)
    {
        if (auth()->user()->isAdmin()){
            $user = User::find($userID);
            if ($user === null || !($user->isClient() || $user->isNewClient())) abort(404);

            $subcategories = Subcategory::all();

            $data = array(
                'user'=>$user,
                'subcategories'=>$subcategories
            );

            foreach ($data['subcategories'] as $subcategory){
                $subcategory->discount = $user->getDiscount($subcategory);
            }

            return view('pages.discounts.edit')->with($data);
        }
        else abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
