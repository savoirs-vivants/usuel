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
                <form class="space-y-5" action="#" method="POST">
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
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            required
                            class="w-full bg-gray-50 border-2 border-gray-200 text-gray-900 text-sm rounded-lg
                                   focus:ring-0 focus:border-sv-green outline-none
                                   block p-3 transition-colors duration-200">
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

@endsection
