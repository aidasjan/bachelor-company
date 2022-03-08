<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UsersController extends Controller
{

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
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

    public function showTutorial()
    {
        if (auth()->user()->isClient() || auth()->user()->isAdmin()) {
            return view('pages.client.tutorial');
        }
    }

    public function password()
    {
        if (auth()->user()->isNewClient() || auth()->user()->isClient() || auth()->user()->isAdmin()) {
            return view('pages.client.password');
        } else abort(404);
    }

    public function passwordChange(Request $request)
    {
        if (auth()->user()->isNewClient() || auth()->user()->isClient() || auth()->user()->isAdmin()) {
            $this->validatePasswordChangeRequest($request);
            $this->userService->changePassword($request);
            return redirect('/dashboard');
        } else abort(404);
    }

    private function validatePasswordChangeRequest(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed']
        ]);
    }

    public function resetPassword($id)
    {
        if (auth()->user()->isAdmin()) {
            [$user, $randomPassword] = $this->userService->resetPassword($id);
            $data = array(
                'resetUserEmail' => $user->email,
                'resetUserPassword' => $randomPassword
            );
            return redirect('users/' . $id . '/edit')->with($data);
        } else abort(404);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $user = $this->userService->find($id);
            if ($user === null) abort(404);
            return view('pages.admin.users.edit')->with('user', $user);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateUpdateRequest($request);
            $user = $this->userService->update($request, $id);
            if ($user == null) abort(404);
            return redirect('/users');
        } else abort(404);
    }

    private function validateUpdateRequest(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255']
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $user = $this->userService->destroy($id);
            if ($user === null) abort(404);
            return redirect('/users');
        } else abort(404);
    }
}
