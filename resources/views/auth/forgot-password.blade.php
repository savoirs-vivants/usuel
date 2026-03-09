@extends('layouts.app')
@section('title', 'Usuel - Mot de passe oublié')
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
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 hover:text-sv-blue transition-colors mb-6 group">
                    <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à la connexion
                </a>

                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-sv-blue flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-sv-green uppercase tracking-widest">Sécurité</p>
                        <h1 class="font-mono font-bold text-2xl text-sv-blue leading-tight">Mot de passe oublié</h1>
                    </div>
                </div>
                <p class="text-gray-400 text-sm mt-3 leading-relaxed">
                    Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>
            </div>

            <div class="h-px bg-gray-100 mx-8"></div>

            <div class="px-8 py-7">

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-sv-green/8 border border-sv-green/20 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sv-green/15 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-sv-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sv-green text-sm">Email envoyé !</p>
                            <p class="text-sv-green/60 text-xs mt-0.5 leading-relaxed">{{ session('success') }} Pensez à vérifier vos spams.</p>
                        </div>
                    </div>
                @endif

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

                <form method="POST" action="{{ route('password.send') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-2">
                            Adresse e-mail
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                placeholder="votre@email.fr"
                                class="w-full bg-gray-50 border-2 border-gray-200 hover:border-gray-300 focus:border-sv-green outline-none rounded-2xl pl-14 pr-4 py-3.5 text-sm font-medium text-gray-700 transition-colors placeholder-gray-300">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-sv-green hover:bg-sv-green/90 text-white font-bold text-sm rounded-2xl py-4 transition-all duration-150 shadow-lg shadow-sv-green/25 hover:shadow-sv-green/40 hover:-translate-y-0.5 active:translate-y-0">
                        Envoyer le lien de réinitialisation
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-white/30 text-xs mt-6">Lien valable 60 minutes après réception</p>
    </div>
</section>

@endsection