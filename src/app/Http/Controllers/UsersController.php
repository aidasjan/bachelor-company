<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserErrorException;
use App\User;
use App\Order;
use App\OrderProduct;
use App\Discount;

class UsersController extends Controller
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
        if (auth()->user()->isAdmin()){
            $users = User::all();
            return view('pages.admin.users.index')->with('users', $users);
        }
        else abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->isAdmin()){
            return view('pages.admin.users.register');
        }
        else return abort(404);
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
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255']
            ]);

            // Check if email is unique
            $user_exists = User::where('email_h', hash('sha1', $request->input('email')))->exists();
            if ($user_exists) {
                throw new UserErrorException('This user already exists.'); return;
            }

            // Add new user
            $user = new User;
            $user->name = encrypt($request->input('name'));
            $user->email_h = hash('sha1', $request->input('email'));
            $user->email = encrypt($request->input('email'));
            $user->role = encrypt('client');

            $rand_password = Str::random(10);
            $user->password = Hash::make($rand_password);
            $user->save();

            $data = array(
                'init_user_email'=>$request->input('email'),
                'init_user_password'=>$rand_password
            );

            return redirect('register')->with($data);
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
     * Display the tutorial.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTutorial()
    {
        if (auth()->user()->isClient() || auth()->user()->isAdmin()){
            return view('pages.client.tutorial');
        }
    }

    /**
     * Show password change form for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        if (auth()->user()->isNewClient() || auth()->user()->isClient() || auth()->user()->isAdmin()){
            return view('pages.client.password');
        }
        else abort(404);
    }

    /**
     * Change password for user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordChange(Request $request)
    {
        if (auth()->user()->isNewClient() || auth()->user()->isClient() || auth()->user()->isAdmin()){
            $this->validate($request,[
                'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed']
            ]);
            
            $password = $request->input('password');

            // Validate password
            if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                throw new UserErrorException(__('main.password_not_secure'));
                return;
            }

            // Edit user password
            $user = User::find(auth()->user()->id);
            if ($user === null) {
                abort(404); return;
            }
            $user->password = Hash::make($password);
            $user->is_new = 0;
            $user->save();

            return redirect('/dashboard');

        }
        else abort(404);
    }

    /**
     * Reset user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $id)
    {
        if (auth()->user()->isAdmin()){

            $user = User::find($id);
            if ($user === null) abort(404);

            $rand_password = Str::random(10);
            $user->password = Hash::make($rand_password);
            $user->is_new = 1;
            $user->save();

            $data = array(
                'reset_user_email'=>$user->email,
                'reset_user_password'=>$rand_password
            );

            return redirect('users/'.$id.'/edit')->with($data);
        }
        else abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (auth()->user()->isAdmin()){
            $user = User::find($id);
            if ($user === null) abort(404);
            return view('pages.admin.users.edit')->with('user', $user);
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
        if (auth()->user()->isAdmin()){

            $this->validate($request,[
                'name' => ['required', 'string', 'max:255']
            ]);

            $user = User::find($id);
            if ($user === null) abort(404);
            $user->name = encrypt($request->input('name'));
            $user->save();

            return redirect('/users');
        }
        else abort(404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->isAdmin()){

            $user = User::find($id);
            if ($user === null) abort(404);

            $orders = Order::where('user_id', $user->id)->get();

            // Delete orders that the user has made
            foreach ($orders as $order){
                $order_products = OrderProduct::where('order_id', $order->id)->get();
                foreach ($order_products as $order_product){
                    $order_product->delete();
                }
                $order->delete();
            }

            $discounts = Discount::where('user_id', $user->id)->get();
            // Delete discount asigned to user
            foreach ($discounts as $discount){
                $discount->delete();
            }

            $user->delete();
            return redirect('/users');

        }
        else abort(404);
    }
}
