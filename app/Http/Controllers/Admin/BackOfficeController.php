<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BackOfficeController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
        ->where('is_registered', 1)
        ->latest()
        ->get();
        return view('admin.backoffice', compact('users'));
    }
        
}
