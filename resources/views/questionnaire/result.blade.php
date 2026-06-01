@extends('layouts.app')
@section('title', 'Résultats')

@php
    use App\Services\OrientationService;

    $scores    = $passation->score ?? [];
    $labelsMap = [
        'Resilience' => 'Résilience',
        'EC'         => 'Esprit Critique',
        'CSDLEN'     => 'Comportements sociaux',
        'CT'         => 'Comp. Technique',
        'TDLinfo'    => "Traitement de l'info",
        'CDC'        => 'Création de contenu',
    ];
    $iconsMap  = [
        'Resilience' => '🛡️',
        'EC'         => '🧠',
        'CSDLEN'     => '🤝',
        'CT'         => '⚙️',
        'TDLinfo'    => '🔍',
        'CDC'        => '✏️',
    ];
    $catColors = [
        'Resilience' => '#3b82f6',
        'EC'         => '#8b5cf6',
        'CSDLEN'     => '#ec4899',
        'CT'         => '#f97316',
        'TDLinfo'    => '#10b981',
        'CDC'        => '#f59e0b',
    ];

    $scoreTotal    = $passation->score_total;
    $maxTotal      = 30.0;
    $minTotal      = -30.0;
    $maxParCat     = 5.0;
    $scorePct      = round((($scoreTotal - $minTotal) / ($maxTotal - $minTotal)) * 100);
    $circumference = round($scorePct * 3.14159);

    $parcours = $passation->scenario;
    if (!$parcours) {
        $passation->loadMissing('beneficiaire');
        OrientationService::compute($passation);
        $passation->refresh();
        $parcours = $passation->scenario;
    }

    $parcoursLabel  = OrientationService::PARCOURS_LABELS[$parcours]  ?? '–';
    $orientation    = OrientationService::ORIENTATIONS[$parcours]     ?? '–';
    $modules        = $passation->modules ?? OrientationService::MODULES[$parcours] ?? [];

    // Description détaillée de chaque module — affichée dans la modale Suggestion.
    // Explique concrètement ce que le bénéficiaire va travailler dans chaque bloc.
    $moduleDescriptions = [
        'Bloc 1 – Environnement numérique'    => "Apprendre à utiliser un ordinateur, une tablette ou un smartphone, naviguer sur internet, gérer ses fichiers et paramétrer ses appareils pour gagner en autonomie au quotidien.",
        'Bloc 2 – Démarches administratives'  => "Réaliser ses démarches en ligne (impôts, CAF, santé, emploi), créer et sécuriser ses comptes sur les services officiels, et utiliser FranceConnect en toute confiance.",
        'Bloc 3 – Sécurité numérique'         => "Reconnaître les tentatives d'arnaque et de phishing, protéger ses données personnelles, gérer ses mots de passe et adopter les bons réflexes pour naviguer sans risque.",
        'Bloc 4 – Communication'              => "Échanger par email et messagerie instantanée, utiliser la visioconférence, et employer les réseaux sociaux de manière adaptée, respectueuse et sécurisée.",
        'Bloc 5 – Recherche & Information'    => "Trouver des informations fiables sur internet, savoir croiser les sources, identifier les contenus trompeurs et exercer son esprit critique face à l'information numérique.",
        'Bloc 6 – Création de contenus'       => "Rédiger et mettre en forme des documents, créer des présentations, produire des contenus visuels ou textuels et les partager efficacement en ligne.",
        'Certification GRETA'                  => "Se préparer à une certification officielle de compétences numériques reconnue par l'Éducation nationale, valorisable auprès des employeurs et organismes de formation.",
    ];

    $parcoursColor = match(true) {
        in_array($parcours, ['A', 'B'])       => 'red',
        in_array($parcours, ['C'])            => 'orange',
        in_array($parcours, ['D', 'E', 'F', 'G']) => 'blue',
        $parcours === 'H'                     => 'green',
        default                               => 'gray',
    };
    $parcoursColorMap = [
        'red'    => ['bg' => '#fee2e2', 'text' => '#dc2626', 'border' => '#fca5a5'],
        'orange' => ['bg' => '#ffedd5', 'text' => '#ea580c', 'border' => '#fdba74'],
        'blue'   => ['bg' => '#dbeafe', 'text' => '#2563eb', 'border' => '#93c5fd'],
        'green'  => ['bg' => '#dcfce7', 'text' => '#16a34a', 'border' => '#86efac'],
        'gray'   => ['bg' => '#f3f4f6', 'text' => '#6b7280', 'border' => '#d1d5db'],
    ];
    $pColor = $parcoursColorMap[$parcoursColor];
@endphp

@section('content')
    <div class="h-screen flex flex-col dot-grid" x-data="{ suggestionOpen: false }" @open-suggestion.window="suggestionOpen = true">
        <div class="res-header shrink-0 bg-white border-b border-gray-100 px-8 pt-4 pb-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                @if (auth()->user()->role !== 'admin')
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1a9e7e] to-[#1a2340] flex items-center justify-center shadow-md shadow-[#1a9e7e]/20">
                    <span class="text-sm font-bold text-white">
                        {{ strtoupper(substr($passation->beneficiaire->prenom, 0, 1)) }}{{ strtoupper(substr($passation->beneficiaire->nom, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-[#1a2340] font-bold text-sm leading-tight">
                        {{ $passation->beneficiaire->prenom }} {{ $passation->beneficiaire->nom }}
                    </p>
                    <p class="text-gray-400 text-xs">{{ $passation->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                @endif

                <div class="h-7 w-px bg-gray-200 mx-1"></div>

                <div class="flex items-center gap-2 bg-[#1a9e7e]/8 border border-[#1a9e7e]/20 rounded-xl px-3 py-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#1a9e7e] animate-pulse"></div>
                    <span class="text-[#1a9e7e] text-xs font-bold uppercase tracking-widest">Test terminé</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('passation.certificat', $passation->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-[#1a9e7e]/40 text-gray-500 hover:text-[#1a9e7e] font-semibold px-4 py-2 rounded-xl transition-all duration-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Certificat
                </a>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] active:scale-95 text-white font-semibold px-4 py-2 rounded-xl transition-all duration-200 text-sm shadow-md shadow-[#1a9e7e]/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <div class="flex-1 flex gap-4 px-8 py-5 min-h-0">

            <div class="res-left w-56 shrink-0 flex flex-col gap-3">

                <div class="card rounded-2xl p-4 flex flex-col items-center justify-center relative overflow-hidden shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-b from-[#1a9e7e]/4 via-transparent to-transparent pointer-events-none rounded-2xl"></div>

                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-3 relative z-10">Score global</p>

                    <div class="relative w-28 h-28 mb-2 z-10">
                        <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="#e5e7eb" stroke-width="12"/>
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="#1a9e7e" stroke-width="18"
                                stroke-linecap="round" opacity="0.12"
                                stroke-dasharray="{{ $circumference }} 314"/>
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="url(#dialGradientLight)" stroke-width="12"
                                stroke-linecap="round"
                                class="res-dial"
                                style="stroke-dasharray: {{ $circumference }} 314"/>
                            <defs>
                                <linearGradient id="dialGradientLight" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%"   stop-color="#1a9e7e"/>
                                    <stop offset="100%" stop-color="#34d399"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="res-score text-3xl font-bold text-[#1a2340] tabular-nums">{{ $scoreTotal }}</span>
                            <span class="text-gray-400 text-[10px] font-semibold">/ {{ (int)$maxTotal }} pts</span>
                        </div>
                    </div>
                </div>

                <button @click="$dispatch('open-suggestion')" class="card rounded-2xl p-4 flex flex-col items-center justify-center gap-2 flex-1 bg-orange-50/60 border-2 border-orange-100 hover:border-orange-400 hover:bg-orange-50 hover:shadow-xl hover:shadow-orange-100 transition-all duration-300 group cursor-pointer">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-1 bg-orange-100/50 group-hover:bg-orange-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="#f97316" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>

                    <span class="text-xl font-black text-orange-600 leading-tight">
                        Et maintenant ?
                    </span>

                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-orange-600 text-white group-hover:scale-110 transition-transform">
                        Découvrir
                    </span>
                </button>
            </div>

            <div class="res-radar flex-1 card rounded-2xl p-6 flex flex-col min-w-0 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-[#1a9e7e]/6 to-transparent pointer-events-none rounded-2xl"></div>

                <div class="flex items-center justify-between mb-4 shrink-0 relative z-10">
                    <div>
                        <p class="shimmer-label font-bold text-lg">Profil de compétences</p>
                    </div>
                </div>

                <div class="flex-1 relative min-h-0">
                    <canvas id="radarChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <div class="res-right w-60 shrink-0 flex flex-col gap-2.5 overflow-y-auto">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 shrink-0">Diagnostic</p>

                @foreach ($scores as $clé => $score)
                    @php
                        $score   = (float) $score;
                        $label   = $labelsMap[$clé] ?? $clé;
                        $icon    = $iconsMap[$clé] ?? '📊';
                        $color   = $catColors[$clé] ?? '#6b7280';
                        $barPct  = max(0, min(100, round((($score + $maxParCat) / ($maxParCat * 2)) * 100)));
                        $isPos   = $score >= 0;
                        $cardIdx = $loop->iteration;
                    @endphp
                    <div class="res-card-{{ $cardIdx }} card hover:shadow-md rounded-xl px-4 py-3 transition-all duration-200 group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-base leading-none">{{ $icon }}</span>
                                <span class="text-[#1a2340] text-xs font-semibold truncate">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-bold tabular-nums px-2 py-0.5 rounded-full shrink-0
                                {{ $isPos ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-500 border border-red-100' }}">
                                {{ $isPos ? '' : '' }}{{ $score }}
                            </span>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="res-bar h-full rounded-full group-hover:brightness-110 transition-all"
                                 style="width: {{ $barPct }}%; background: linear-gradient(90deg, {{ $color }}80, {{ $color }})">
                            </div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-gray-300 text-[10px]">-{{ (int)$maxParCat }}</span>
                            <span class="text-gray-300 text-[10px]">{{ (int)$maxParCat }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="chart-data" class="hidden"
             data-radar-scores='@json($scores)'
             data-radar-labels='@json($labelsMap)'
             data-radar-colors='@json($catColors)'>
        </div>

        {{-- ── Modale Suggestion ───────────────────────────────────────────────── --}}
        {{-- Présente le parcours, l'orientation et les modules avec leurs          --}}
        {{-- descriptions détaillées. Alpine.js gère l'ouverture/fermeture.        --}}
        <div
            x-show="suggestionOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-6"
            style="background:rgba(15,23,42,0.6); backdrop-filter:blur(4px)"
            @click.self="suggestionOpen = false"
            x-cloak>

            <div
                x-show="suggestionOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-xl max-h-[85vh] overflow-y-auto rounded-3xl shadow-2xl"
                style="background:white">

                {{-- ── En-tête dégradé ──────────────────────────────────────────── --}}
                <div class="relative overflow-hidden rounded-t-3xl px-8 pt-7 pb-6"
                     style="background:linear-gradient(135deg,#1a2340 0%,#132d4a 55%,#0d3d2e 100%)">
                    {{-- Cercle décoratif --}}
                    <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full pointer-events-none"
                         style="background:rgba(26,158,126,0.1)"></div>

                    {{-- Bouton fermer --}}
                    <button @click="suggestionOpen = false"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center transition-colors"
                            style="background:rgba(255,255,255,0.1)" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Titre --}}
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#1a9e7e">
                        Suggestion personnalisée
                    </p>

                    {{-- Parcours en grand --}}
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center font-mono font-extrabold text-2xl shrink-0"
                              style="width:3.2rem;height:3.2rem;border-radius:50%;background:#1a9e7e;
                                     color:white;box-shadow:0 0 0 5px rgba(26,158,126,0.2)">
                            {{ $parcours }}
                        </span>
                        <div>
                            <p class="font-extrabold text-white text-xl leading-tight">{{ $parcoursLabel }}</p>
                            <p class="text-sm mt-0.5" style="color:rgba(255,255,255,0.5)">Parcours d'orientation attribué</p>
                        </div>
                    </div>
                </div>

                {{-- ── Corps de la modale ────────────────────────────────────────── --}}
                <div class="px-8 py-6 flex flex-col gap-6">

                    {{-- Bloc orientation --}}
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Nous lui recommandons de s'orienter vers</p>
                        <div class="flex items-start gap-3 p-4 rounded-2xl border border-[#1a9e7e]/20 bg-[#1a9e7e]/5">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                                 style="background:#1a9e7e">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-[#1a2340] text-sm">{{ $orientation }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-snug">
                                    @if(in_array($parcours, ['A','B','C']))
                                        Un conseiller dédié prendra en charge le suivi de ce bénéficiaire et l'accompagnera pas à pas dans sa montée en compétences.
                                    @elseif($parcours === 'H')
                                        Le niveau atteint ne nécessite pas d'accompagnement spécifique. Une certification officielle peut venir valoriser ces acquis.
                                    @else
                                        Une inscription à un stage Savoirs Vivants lui permettra de progresser sur ses points de fragilité dans un cadre bienveillant et structuré.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Modules prioritaires avec descriptions --}}
                    @if(!empty($modules))
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Modules prioritaires à travailler</p>
                        <div class="flex flex-col gap-3">
                            @foreach($modules as $i => $module)
                            @php $desc = $moduleDescriptions[$module] ?? ''; @endphp
                            <div class="flex items-start gap-3 p-4 rounded-2xl border border-gray-100 bg-gray-50">
                                {{-- Numéro du module --}}
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-extrabold shrink-0 mt-0.5"
                                      style="background:#1a2340;color:#1a9e7e">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <p class="font-bold text-[#1a2340] text-sm leading-snug">{{ $module }}</p>
                                    @if($desc)
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $desc }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Bouton fermer bas --}}
                    <button @click="suggestionOpen = false"
                            class="w-full py-3 rounded-2xl font-bold text-sm text-white transition-all duration-200 active:scale-95"
                            style="background:linear-gradient(135deg,#1a2340,#0d3d2e)"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Fermer
                    </button>

                </div>
            </div>
        </div>
    </div>
@endsection
