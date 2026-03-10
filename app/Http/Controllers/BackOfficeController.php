<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BackOfficeController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $search = $request->query('search');

        $query = User::query();

        $query->where('id', '!=', Auth::id());

        if (Auth::user()->role === 'gestionnaire') {
            $query->where('structure', Auth::user()->structure)
                  ->where('role', 'travailleur');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('structure', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return view('backoffice', compact('users', 'search'));
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
