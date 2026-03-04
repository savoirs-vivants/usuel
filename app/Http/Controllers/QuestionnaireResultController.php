<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionnaireResultController extends Controller
{
    public function show($id)
    {
        $passation = Passation::with('beneficiaire')->findOrFail($id);
        Gate::authorize('view', $passation);
        return view('questionnaire.result', compact('passation'));
    }
}
