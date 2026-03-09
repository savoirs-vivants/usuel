@extends('layouts.app')
@section('title', 'Usuel - Nouveau mot de passe')
@section('content')

<section class="bg-sv-blue min-h-screen flex items-center justify-center px-6 py-12 relative overflow-hidden">

    <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/5 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-sv-green/10 translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

    <div class="w-full sm:max-w-md relative z-10">

        <div class="flex justify-center mb-8">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-3">
                <span class="font-mono font-bold text-xl text-white tracking-tight">Usuel</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl shadow-sv-blue/40 overflow-hidden">

            <div class="px-8 pt-8 pb-7">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-sv-green flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-sv-green uppercase tracking-widest">Sécurité</p>
                        <h1 class="font-mono font-bold text-2xl text-sv-blue leading-tight">Nouveau mot de passe</h1>
                    </div>
                </div>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Choisissez un mot de passe sécurisé d'au moins 8 caractères.
                </p>
            </div>

            <div class="h-px bg-gray-100 mx-8"></div>

            <div class="px-8 py-7">

                @if ($errors->any())
                    <div class="mb-6 p-3.5 rounded-2xl bg-red-50 border border-red-100 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-red-600 text-xs font-bold">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-2">
                            Nouveau mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                placeholder="8 caractères minimum"
                                class="w-full bg-gray-50 border-2 border-gray-200 hover:border-gray-300 focus:border-sv-green outline-none rounded-2xl pl-14 pr-12 py-3.5 text-sm font-medium text-gray-700 transition-colors placeholder-gray-300">
                            <button type="button" onclick="togglePassword('password', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-300 hover:text-sv-green transition-colors">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-2">
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                placeholder="Répétez le mot de passe"
                                class="w-full bg-gray-50 border-2 border-gray-200 hover:border-gray-300 focus:border-sv-green outline-none rounded-2xl pl-14 pr-12 py-3.5 text-sm font-medium text-gray-700 transition-colors placeholder-gray-300">
                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-300 hover:text-sv-green transition-colors">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-sv-blue hover:bg-sv-blue/90 text-white font-bold text-sm rounded-2xl py-4 transition-all duration-150 shadow-lg shadow-sv-blue/25 hover:shadow-sv-blue/40 hover:-translate-y-0.5 active:translate-y-0">
                        Enregistrer le nouveau mot de passe
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
        button.classList.toggle('text-sv-green');
        button.classList.toggle('text-gray-300');
    }
</script>

@endsection