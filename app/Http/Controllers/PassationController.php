<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;

class PassationController extends Controller
{
    public function index()
    {
        $passations = Passation::with('beneficiaire', 'user')->where('consentement_recherche', 1)->latest()->get();
        return view('passation', compact('passations'));
    }

    public function certificat(Passation $passation)
    {
        return view('questionnaire.certificat', compact('passation'));
    }

        public function destroy(Passation $passation)
    {
        $passation->delete();

        session()->flash('toast_message', 'Passation supprimé');
        session()->flash('toast_type', 'success');

        return redirect()->route('passations');
    }
}
