<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct(UserService $userService)
    {
        $this->middleware('auth')->except(['login']);
        $this->userService = $userService;
    }

    public function login($id, $accessToken) 
    {
        $user = $this->userService->find($id);
        if ($user == null || $user->access_token !== $accessToken) {
           abort(401);
        }
        Auth::login($user);
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $users = $this->userService->all();
            return view('pages.admin.users.index')->with('users', $users);
        } else abort(404);
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.admin.users.register');
        } else return abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $randomPassword = $this->userService->store($request);

            $data = array(
                'newUserEmail' => $request->input('email'),
                'newUserPassword' => $randomPassword
            );

            return redirect('register')->with($data);
        } else abort(404);
    }

    private function validateStoreRequest(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255']
        ]);
    }
}
