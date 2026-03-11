<?php

namespace App\Http\Controllers;

use App\Models\Passation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class PassationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->query('search');
        $perPage = $request->query('per_page', 5);

        $query = Passation::with(['beneficiaire', 'user'])->orderBy('date', 'desc');

        if ($user->role === 'travailleur') {
            $query->where('id_travailleur', $user->id);
        } elseif ($user->role === 'gestionnaire') {
            $query->whereHas('user', function (Builder $q) use ($user) {
                $q->where('structure', $user->structure);
            });
        } else {
            $query->where('consentement_recherche', 1);
        }

        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('beneficiaire', function (Builder $qb) use ($search) {
                      $qb->where('nom', 'LIKE', "%{$search}%")
                         ->orWhere('prenom', 'LIKE', "%{$search}%");
                  });
            });
        }

        $passations = $query->paginate($perPage)->withQueryString();

        return view('passation', compact('passations', 'search', 'perPage'));
    }

    public function certificat(Passation $passation)
    {
        Gate::authorize('view', $passation);

        return view('questionnaire.certificat', compact('passation'));
    }

    public function destroy(Passation $passation)
    {
        Gate::authorize('delete', $passation);

        $beneficiaire = $passation->beneficiaire;

        $passation->delete();

        if ($beneficiaire->passations()->count() === 0) {
            $beneficiaire->delete();
        }

        session()->flash('toast_message', 'Passation supprimée');
        session()->flash('toast_type', 'success');

        return redirect()->route('passations');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        $passations = Passation::whereIn('id', $ids)->with('beneficiaire')->get();
        $beneficiairesAverifier = collect();

        foreach ($passations as $p) {
            Gate::authorize('delete', $p);
            if ($p->beneficiaire) {
                $beneficiairesAverifier->push($p->beneficiaire);
            }
        }

        Passation::whereIn('id', $ids)->delete();

        $beneficiairesAverifier = $beneficiairesAverifier->unique('id');

        foreach ($beneficiairesAverifier as $beneficiaire) {
            if ($beneficiaire->passations()->count() === 0) {
                $beneficiaire->delete();
            }
        }

        session()->flash('toast_message', count($ids) . ' passation(s) supprimée(s)');
        session()->flash('toast_type', 'success');

        return redirect()->route('passations');
    }
}
