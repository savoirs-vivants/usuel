<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;

class PassationController extends Controller
{
    public function index()
    {
        $passations = Passation::with('beneficiaire')->latest()->get();
        return view('passation', compact('passations'));
    }
}
