<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class PassationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Passation::with(['beneficiaire', 'user'])->latest();

        if ($user->role === 'travailleur') {
            $query->where('id_travailleur', $user->id);

        } elseif ($user->role === 'gestionnaire') {
            $query->whereHas('user', function (Builder $q) use ($user) {
                $q->where('structure', $user->structure);
            });

        } else {
            $query->where('consentement_recherche', 1);
        }

        $passations = $query->get();

        return view('passation', compact('passations'));
    }

    public function certificat(Passation $passation)
    {
        Gate::authorize('view', $passation);

        return view('questionnaire.certificat', compact('passation'));
    }

    public function destroy(Passation $passation)
    {
        Gate::authorize('delete', $passation);

        $passation->delete();

        session()->flash('toast_message', 'Passation supprimée');
        session()->flash('toast_type', 'success');

        return redirect()->route('passations');
    }
}
