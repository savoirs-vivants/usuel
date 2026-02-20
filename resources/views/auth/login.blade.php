@extends('layouts.app')

@section('title', 'Usuel - Inscription')

@section('content')

<section class="bg-sv-blue min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full sm:max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-sv-green px-8 pt-8 pb-6 border-b-4 border-sv-green">
                <h1 class="font-mono font-bold text-2xl text-white leading-snug">
                    Connexion
                </h1>
            </div>
            <div class="px-8 pb-8">
                <form class="space-y-5" action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block mb-1.5 text-sm font-bold text-sv-blue">
                            Adresse e-mail
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm rounded-lg
                                   focus:ring-0 focus:border-sv-green outline-none
                                   block p-3 transition-colors duration-200">
                    </div>
                    <div>
                        <label for="password" class="block mb-1.5 text-sm font-bold text-sv-blue">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm rounded-lg
                                       focus:ring-0 focus:border-sv-green outline-none
                                       block p-3 pr-10 transition-colors duration-200">
                            <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-sv-green">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-sv-green text-white font-bold text-sm rounded-lg
                               px-5 py-3 hover:opacity-90 transition-opacity duration-200 mt-2">
                        Se connecter à mon compte →
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            button.classList.add('text-sv-green');
            button.classList.remove('text-gray-400');
        } else {
            input.type = "password";
            button.classList.remove('text-sv-green');
            button.classList.add('text-gray-400');
        }
    }
</script>

@endsection
