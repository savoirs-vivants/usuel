<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BackOfficeController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $query = User::query();

        $query->where('id', '!=', Auth::id());

        if (Auth::user()->role === 'gestionnaire') {
            $query->where('structure', Auth::user()->structure)
                ->where('role', 'travailleur');
        }

        $users = $query->get();

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

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        $ids = array_filter($ids, fn($id) => $id != Auth::id());

        User::whereIn('id', $ids)->delete();

        session()->flash('toast_message', count($ids) . ' compte(s) supprimé(s)');
        session()->flash('toast_type', 'success');

        return redirect()->route('backoffice');
    }
}
