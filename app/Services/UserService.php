<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserErrorException;
use App\Mail\InvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        Mail::to($request->input('email'))->send(new InvitationMail($request->input('email'), $randomPassword));
    }

    private function generateRandomPassword()
    {
        return Str::random(10);
    }
}
