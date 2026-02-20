@extends('layouts.app')

@section('title', 'Finaliser mon inscription')

@section('content')
<section class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">

        <h1 class="font-mono font-bold text-2xl text-sv-blue mb-1">Finaliser votre inscription</h1>
        <p class="text-gray-400 text-sm mb-6">Bienvenue {{ $user->firstname ?? $user->name }}, choisissez votre mot de passe.</p>

        <form method="POST" action="{{ route('inscription.complete', $token) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-sv-blue mb-1">Mot de passe</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl p-3 outline-none focus:border-sv-green text-sm">
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-sv-blue mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl p-3 outline-none focus:border-sv-green text-sm">
            </div>

            <button type="submit"
                class="w-full mt-2 bg-sv-green hover:opacity-90 text-white font-bold py-3 rounded-xl transition-opacity">
                Confirmer et accéder à la plateforme
            </button>
        </form>
    </div>
</section>
@endsection
