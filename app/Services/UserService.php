<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserErrorException;
use App\Models\User;

class UserService
{

    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function store(Request $request)
    {

        $userExists = User::where('email_h', hash('sha1', $request->input('email')))->exists();
        if ($userExists) {
            throw new UserErrorException('This user already exists.');
            return;
        }

        $user = new User;
        $user->name = encrypt($request->input('name'));
        $user->email_h = hash('sha1', $request->input('email'));
        $user->email = encrypt($request->input('email'));
        $user->role = encrypt('client');

        $randomPassword = $this->generateRandomPassword();
        $user->password = Hash::make($randomPassword);
        $user->save();

        return $randomPassword;
    }

    public function changePassword(Request $request)
    {
        $password = $request->input('password');

        $this->validatePasswordStrength($password);

        $user = auth()->user();
        $user->password = Hash::make($password);
        $user->is_new = 0;
        $user->save();

        return $user;
    }

    private function validatePasswordStrength($password)
    {
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            throw new UserErrorException(__('main.password_not_secure'));
        }
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return null;
        }

        $randomPassword = $this->generateRandomPassword();
        $user->password = Hash::make($randomPassword);
        $user->is_new = 1;
        $user->save();

        return [$user, $randomPassword];
    }

    private function generateRandomPassword()
    {
        return Str::random(10);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            return null;
        }
        $user->name = encrypt($request->input('name'));
        $user->save();
        return $user;
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user === null) return null;
        $user->safeDelete();
        return $user;
    }
}
