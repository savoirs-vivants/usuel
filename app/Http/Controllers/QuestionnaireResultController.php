<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;

class QuestionnaireResultController extends Controller
{
    public function show($id)
    {
        $passation = Passation::with('beneficiaire')->findOrFail($id);
        return view('questionnaire.result', compact('passation'));
    }
}
