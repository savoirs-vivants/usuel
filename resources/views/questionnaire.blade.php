@extends('layouts.app')

@section('title', 'Usuel - Questionnaire')

@section('content')

    <div class="ml-[230px] min-h-screen bg-gray-50 px-10 py-10">

        <div class="gap-8 mb-8">
            <div class="mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-1">Module d'évaluation</p>
                <h1 class="font-mono font-bold text-3xl text-[#1a2340]">
                    Littératie numérique
                </h1>
                <p class="text-gray-400 text-sm mt-1 max-w-lg">
                    Identifiez les besoins numériques de vos publics grâce à ce questionnaire intuitif et simple
                    d'utilisation.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @can('viewAny', App\Models\Question::class)
                    @livewire('mode-selector')
                    <div x-data="{ show: false, message: '' }"
                        x-on:notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
                        x-show="show" x-transition.opacity.duration.300ms style="display: none;"
                        class="fixed bottom-10 right-10 bg-[#1a9e7e] text-white px-6 py-3 rounded-lg shadow-xl font-medium text-sm z-50 flex items-center gap-2">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span x-text="message"></span>
                    </div>

                    <a href="{{ route('questions.gestion')}}"
                        class="inline-flex items-center gap-2 bg-white border border-gray-200 text-[#1a2340] text-sm font-medium rounded-lg px-4 py-2.5 hover:border-[#1a9e7e] hover:text-[#1a9e7e] hover:bg-emerald-50 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Gérer les questions
                    </a>
                @endcan

                @livewire('questionnaire-modal')
            </div>
        </div>

        <div class="grid grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-xl p-5 flex items-center gap-4 shadow-sm border border-gray-100">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Questions</p>
                    <p class="text-2xl font-bold text-[#1a2340]">30</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-6">

            <div class="col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-5">Thématiques couvertes</p>

                <div class="grid grid-cols-2 gap-3">

                    @php
                        $themes = [
                            [
                                'emoji' => '🛡️',
                                'label' => 'Résilience',
                                'desc' => 'Capacité à faire face aux obstacles numériques',
                                'bg' => 'bg-blue-50',
                                'text' => 'text-blue-700',
                                'border' => 'border-blue-100',
                                'dot' => 'bg-blue-400',
                            ],
                            [
                                'emoji' => '🧠',
                                'label' => 'Esprit Critique',
                                'desc' => 'Analyse et vérification des sources en ligne',
                                'bg' => 'bg-purple-50',
                                'text' => 'text-purple-700',
                                'border' => 'border-purple-100',
                                'dot' => 'bg-purple-400',
                            ],
                            [
                                'emoji' => '🤝',
                                'label' => 'Comportements sociaux',
                                'desc' => 'Usages responsables dans les environnements numériques',
                                'bg' => 'bg-pink-50',
                                'text' => 'text-pink-700',
                                'border' => 'border-pink-100',
                                'dot' => 'bg-pink-400',
                            ],
                            [
                                'emoji' => '⚙️',
                                'label' => 'Compétence technique',
                                'desc' => 'Maîtrise des outils et interfaces numériques',
                                'bg' => 'bg-orange-50',
                                'text' => 'text-orange-700',
                                'border' => 'border-orange-100',
                                'dot' => 'bg-orange-400',
                            ],
                            [
                                'emoji' => '🔍',
                                'label' => "Traitement de l'information",
                                'desc' => 'Recherche, lecture et compréhension des données',
                                'bg' => 'bg-emerald-50',
                                'text' => 'text-emerald-700',
                                'border' => 'border-emerald-100',
                                'dot' => 'bg-emerald-400',
                            ],
                            [
                                'emoji' => '✏️',
                                'label' => 'Création de contenu',
                                'desc' => 'Production et partage de contenus numériques',
                                'bg' => 'bg-rose-50',
                                'text' => 'text-rose-700',
                                'border' => 'border-rose-100',
                                'dot' => 'bg-rose-400',
                            ],
                        ];
                    @endphp

                    @foreach ($themes as $theme)
                        <div
                            class="flex items-start gap-3 p-4 rounded-xl border {{ $theme['border'] }} {{ $theme['bg'] }}">
                            <span class="text-xl leading-none shrink-0 mt-0.5">{{ $theme['emoji'] }}</span>
                            <div>
                                <p class="text-sm font-semibold {{ $theme['text'] }} mb-0.5">{{ $theme['label'] }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $theme['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="bg-[#1a2340] rounded-2xl p-6 flex items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Système de pondération adaptatif</p>
                    <p class="text-white/50 text-xs mt-0.5">Chaque réponse rapporte entre −1 et +1 point par compétence. Le
                        score peut être négatif.</p>
                </div>
            </div>
            <div class="flex items-center gap-8 shrink-0">
                <div class="text-center">
                    <p class="text-white font-bold text-xl">+1</p>
                    <p class="text-white/40 text-xs mt-0.5">Bonne réponse</p>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-amber-400 font-bold text-xl">+0.5</p>
                    <p class="text-white/40 text-xs mt-0.5">Partielle</p>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-white/40 font-bold text-xl">0</p>
                    <p class="text-white/40 text-xs mt-0.5">Ne sait pas</p>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-amber-400 font-bold text-xl">−0.5</p>
                    <p class="text-white/40 text-xs mt-0.5">Partiellement mauvaise</p>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-red-400 font-bold text-xl">−1</p>
                    <p class="text-white/40 text-xs mt-0.5">Mauvaise</p>
                </div>
            </div>
        </div>

    </div>
@endsection
