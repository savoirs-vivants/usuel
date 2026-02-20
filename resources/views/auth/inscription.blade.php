@extends('layouts.app')

@section('title', 'Finaliser mon inscription')

@section('content')
<section class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">

        <h1 class="font-mono font-bold text-2xl text-sv-blue mb-1">Finaliser votre inscription</h1>
        <p class="text-gray-400 text-sm mb-6">Bienvenue {{ $user->firstname ?? $user->name }}, choisissez votre mot de passe.</p>

        {{-- Affichage d'une erreur globale si la confirmation échoue --}}
        @if ($errors->has('password'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-xs font-bold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Les mots de passe ne correspondent pas ou sont invalides.
            </div>
        @endif

        <form method="POST" action="{{ route('inscription.complete', $token) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-sv-blue mb-1">Mot de passe</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-50 border-2 @error('password') border-red-300 @else border-gray-200 @enderror rounded-xl p-3 pr-10 outline-none focus:border-sv-green text-sm">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-sv-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-sv-blue mb-1">Confirmer le mot de passe</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full bg-gray-50 border-2 @error('password') border-red-300 @else border-gray-200 @enderror rounded-xl p-3 pr-10 outline-none focus:border-sv-green text-sm">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-sv-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full mt-2 bg-sv-green hover:opacity-90 text-white font-bold py-3 rounded-xl transition-opacity">
                Confirmer et accéder à la plateforme
            </button>
        </form>
    </div>
</section>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        input.type = input.type === "password" ? "text" : "password";
        button.classList.toggle('text-sv-green');
        button.classList.toggle('text-gray-400');
    }
</script>
@endsection
