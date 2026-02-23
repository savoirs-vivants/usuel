@extends('layouts.app')

@section('title', 'Usuel - Back-Office')

@section('content')

    <div class="ml-[230px] min-h-screen bg-gray-50 px-10 py-10">

        <div class="flex items-start justify-between gap-8 mb-8">
            <div>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">
                    Questionnaire sur la littératie numérique
                </h1>
                <p class="text-gray-400 text-sm mt-1">
                    Identifiez les besoins numériques de vos publics grâce à ce questionnaire intuitif et simple d'utilisation.
                </p>
            </div>

            <a href="#"
               class="inline-flex font-mono items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white font-semibold text-sm px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 whitespace-nowrap shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 3l14 9-14 9V3z"/>
                </svg>
                Lancer le questionnaire
            </a>
        </div>

        <div class="flex items-center gap-3 mb-8">

            <div class="relative">
                <select id="mode-select"
                        class="appearance-none bg-white border border-gray-200 text-[#1a2340] text-sm font-medium rounded-lg pl-4 pr-10 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition">
                    <option value="fixe">Mode fixe</option>
                    <option value="aleatoire">Mode aléatoire</option>
                    <option value="semi_aleatoire">Mode semi-aléatoire</option>
                    <option value="carre_latin">Carré latin</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                </div>
            </div>

            <a href="#"
               class="inline-flex items-center gap-2 bg-white border border-gray-200 text-[#1a2340] text-sm font-medium rounded-lg px-4 py-2.5 hover:border-[#1a9e7e] hover:text-[#1a9e7e] hover:bg-emerald-50 transition">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Gérer les questions
            </a>
        </div>

        <div class="grid grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-xl p-5 flex items-center gap-4 shadow-sm border border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Questions</p>
                    <p class="text-2xl font-bold text-[#1a2340]">30</p>
                </div>
            </div>
        </div>

        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Thématiques couvertes</p>
        <div class="flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-sm font-medium px-4 py-1.5 rounded-full">
                🛡️ Résilience
            </span>
            <span class="inline-flex items-center gap-1.5 bg-teal-50 text-teal-700 text-sm font-medium px-4 py-1.5 rounded-full">
                🧠 Esprit Critique
            </span>
            <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-sm font-medium px-4 py-1.5 rounded-full">
                🤝 Comportements sociaux dans les environnements numériques
            </span>
            <span class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 text-sm font-medium px-4 py-1.5 rounded-full">
                ⚙️ Compétence technique
            </span>
            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-sm font-medium px-4 py-1.5 rounded-full">
                🔍 Traitement de l'information
            </span>
            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 text-sm font-medium px-4 py-1.5 rounded-full">
                ✏️ Création de contenu
            </span>
        </div>

    </div>
@endsection
