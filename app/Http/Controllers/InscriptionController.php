<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    public function show(string $token)
    {
        $user = User::where('invitation_token', $token)
                    ->where('is_registered', false)
                    ->firstOrFail();

        return view('auth.inscription', compact('user', 'token'));
    }

    public function complete(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)
                    ->where('is_registered', false)
                    ->firstOrFail();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password'         => Hash::make($request->password),
            'invitation_token' => null,
            'is_registered'    => true,
        ]);

        Auth::login($user);

        return redirect()->route('admin.backoffice');
    }
}
