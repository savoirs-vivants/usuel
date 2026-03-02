<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BackOfficeController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('is_registered', 1)
            ->latest()
            ->get();

        return view('backoffice', compact('users'));
    }

    public function edit(User $user)
    {
        return view('edit-user', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('toast_message', 'Utilisateur supprimé');
        session()->flash('toast_type', 'success');

        return redirect()->route('backoffice');
    }
}
