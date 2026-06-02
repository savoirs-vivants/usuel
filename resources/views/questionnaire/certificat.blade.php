@extends('layouts.print')

@section('title', 'Certificat')

@section('content')

    @php
        use App\Services\OrientationService;

        $scores = $passation->score ?? [];
        $scoreTotal = $passation->score_total ?? array_sum($scores);

        $parcours = $passation->scenario;
        if (!$parcours) {
            $passation->loadMissing('beneficiaire');
            OrientationService::compute($passation);
            $passation->refresh();
            $parcours = $passation->scenario;
        }

        $parcoursLabel = OrientationService::PARCOURS_LABELS[$parcours] ?? '–';
        $modules = $passation->modules ?? (OrientationService::MODULES[$parcours] ?? []);

        $moduleDescriptions = [
            'Bloc 1 – Environnement numérique' =>
                'Apprendre à utiliser un ordinateur, une tablette ou un smartphone, naviguer sur internet, gérer ses fichiers et paramétrer ses appareils pour gagner en autonomie au quotidien.',
            'Bloc 2 – Démarches administratives' =>
                'Réaliser ses démarches en ligne (impôts, CAF, santé, emploi), créer et sécuriser ses comptes sur les services officiels, et utiliser FranceConnect en toute confiance.',
            'Bloc 3 – Sécurité numérique' =>
                "Reconnaître les tentatives d'arnaque et de phishing, protéger ses données personnelles, gérer ses mots de passe et adopter les bons réflexes pour naviguer sans risque.",
            'Bloc 4 – Communication' =>
                'Échanger par email et messagerie instantanée, utiliser la visioconférence, et employer les réseaux sociaux de manière adaptée, respectueuse et sécurisée.',
            'Bloc 5 – Recherche & Information' =>
                "Trouver des informations fiables sur internet, savoir croiser les sources, identifier les contenus trompeurs et exercer son esprit critique face à l'information numérique.",
            'Bloc 6 – Création de contenus' =>
                'Rédiger et mettre en forme des documents, créer des présentations, produire des contenus visuels ou textuels et les partager efficacement en ligne.',
            'Certification GRETA' =>
                "Se préparer à une certification officielle de compétences numériques reconnue par l'Éducation nationale, valorisable auprès des employeurs et organismes de formation.",
        ];

        $phrase = empty($modules)
            ? "Votre maîtrise globale des outils numériques est excellente. Ce niveau d'autonomie est un atout précieux, continuez d'explorer et de valoriser vos compétences !"
            : 'Afin de poursuivre votre progression, de gagner en confiance et de consolider votre autonomie numérique, nous vous recommandons de suivre en priorité les axes suivants :';

        $parcoursColor = match (true) {
            in_array($parcours, ['A', 'B']) => 'red',
            in_array($parcours, ['C']) => 'orange',
            in_array($parcours, ['D', 'E', 'F', 'G']) => 'blue',
            $parcours === 'H' => 'green',
            default => 'gray',
        };

        $parcoursColorMap = [
            'red' => [
                'bg' => '#fee2e2',
                'text' => '#dc2626',
                'border' => '#fca5a5',
                'badgeBg' => 'rgba(220,38,38,0.15)',
                'badgeText' => '#fca5a5',
            ],
            'orange' => [
                'bg' => '#ffedd5',
                'text' => '#ea580c',
                'border' => '#fdba74',
                'badgeBg' => 'rgba(234,88,12,0.15)',
                'badgeText' => '#fdba74',
            ],
            'blue' => [
                'bg' => '#dbeafe',
                'text' => '#2563eb',
                'border' => '#93c5fd',
                'badgeBg' => 'rgba(37,99,235,0.15)',
                'badgeText' => '#93c5fd',
            ],
            'green' => [
                'bg' => '#dcfce7',
                'text' => '#16a34a',
                'border' => '#86efac',
                'badgeBg' => 'rgba(22,163,74,0.15)',
                'badgeText' => '#86efac',
            ],
            'gray' => [
                'bg' => '#f3f4f6',
                'text' => '#6b7280',
                'border' => '#d1d5db',
                'badgeBg' => 'rgba(107,114,128,0.15)',
                'badgeText' => '#d1d5db',
            ],
        ];
        $pColor = $parcoursColorMap[$parcoursColor];
    @endphp

    <button onclick="window.print()"
        class="no-print font-grotesk fixed top-4 right-4 z-50 flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-[#1a9e7e]/25 transition-all duration-200 active:scale-95 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zM9 9V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Générer le PDF
    </button>

    <div class="mx-auto relative overflow-hidden flex flex-row shadow-2xl print:shadow-none print:m-0 bg-white font-grotesk"
        style="width:210mm; min-height:297mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">

        <div class="w-[78mm] shrink-0 text-white relative flex flex-col p-8 overflow-hidden z-10"
            style="background: linear-gradient(135deg, #1a2340 0%, #132d4a 55%, #0d3d2e 100%)">

            <div class="absolute -top-10 -left-10 w-48 h-48 rounded-full pointer-events-none"
                style="background:rgba(26,158,126,0.08)"></div>
            <div class="absolute bottom-10 -right-10 w-40 h-40 rounded-full pointer-events-none"
                style="background:rgba(52,211,153,0.05)"></div>
            <div
                class="absolute top-1/2 left-0 w-full h-px bg-gradient-to-r from-transparent via-[#1a9e7e]/20 to-transparent">
            </div>

            <div class="relative z-10 flex-1 flex flex-col justify-center items-center text-center -mt-12">

                <div class="relative flex items-center justify-center w-40 h-40 mb-8">
                    <div class="absolute inset-0 rounded-full border-4" style="border-color: rgba(26,158,126,0.2)"></div>
                    <div class="absolute inset-2 rounded-full border-2 border-dashed"
                        style="border-color: rgba(52,211,153,0.4)"></div>
                    <span class="text-7xl font-black text-white drop-shadow-lg font-mono">{{ $parcours }}</span>
                </div>

                <h3 class="text-xl font-black text-white leading-tight mb-2 px-2">Parcours <span
                        class="font-mono">{{ $parcours }}</span></h3>
                <p class="text-sm text-slate-300">{{ $parcoursLabel }}</p>

                <div class="mt-16 w-full bg-white/5 rounded-2xl p-6 border border-white/10 backdrop-blur-md shadow-xl">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Score Global</p>
                    <p class="text-5xl font-black text-white font-mono">
                        {{ $scoreTotal }}<span class="text-xl font-medium font-mono" style="color:#1a9e7e">/30</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1 relative bg-white px-12 py-12 flex flex-col justify-between overflow-hidden">

            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[480px] font-black text-slate-50 pointer-events-none select-none z-0 font-mono">
                {{ $parcours }}
            </div>

            <div class="relative z-10 text-right">
                <h1 class="text-3xl font-black text-[#1a2340] uppercase tracking-tighter mb-1">Usuel</h1>
                <p class="font-bold uppercase tracking-[0.2em] text-[10px]" style="color: #1a9e7e">Certificat de compétences
                </p>
                <div class="w-12 h-1.5 ml-auto mt-3 rounded-full" style="background-color: #1a9e7e"></div>
            </div>

            <div class="relative z-10 mt-10 mb-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-4">Délivré avec succès à</p>

                @if (auth()->user()->role !== 'admin')
                    <h2 class="text-5xl font-black text-[#1a2340] uppercase tracking-tight leading-none">
                        {{ $passation->beneficiaire->prenom }}<br>
                        <span style="color: #1a9e7e">{{ $passation->beneficiaire->nom }}</span>
                    </h2>
                @else
                    <div class="w-4/5 h-12 border-b-2 border-slate-100 mb-4"></div>
                    <div class="w-3/5 h-12 border-b-2 border-slate-100"></div>
                @endif
            </div>

            <div class="relative z-10 p-6 rounded-2xl mb-auto shadow-sm border-l-4 flex flex-col gap-4"
                style="background-color: #f8fafc; border-color: #1a9e7e;">

                <svg class="w-8 h-8 absolute -top-4 -left-4 bg-white rounded-full p-1"
                    style="color: {{ $pColor['border'] }}" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>

                <p class="text-slate-700 font-medium text-sm leading-relaxed relative z-10 italic">
                    {{ $phrase }}
                </p>

                @if (!empty($modules))
                    <div class="flex flex-col gap-3 mt-2">
                        @foreach ($modules as $module)
                            @php $desc = $moduleDescriptions[$module] ?? ''; @endphp
                            <div
                                class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1"
                                    style="background-color: {{ $pColor['border'] }}"></div>
                                <h4 class="font-bold text-[#1a2340] text-sm mb-1 ml-2">{{ $module }}</h4>
                                @if ($desc)
                                    <p class="text-xs text-slate-500 leading-snug ml-2">{{ $desc }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="relative z-10 shrink-0 mt-6">

                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[.2em] text-slate-300">Usuel ·
                        {{ $passation->created_at->translatedFormat('d F Y') }}</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                <div class="flex items-end justify-between gap-6">

                    <div class="flex flex-col gap-2 flex-1">
                        <p class="text-[9px] text-slate-400 leading-relaxed max-w-xs">
                            Ce document atteste que le bénéficiaire a complété l'évaluation Usuel
                            et obtenu le profil de compétences présenté, conformément au référentiel Usuel.
                        </p>

                        <div class="relative pointer-events-none select-none" style="height:88px; width:240px">

                            @if (!empty($signatureBase64))
                                <img src="{{ $signatureBase64 }}"
                                    class="absolute bottom-2 left-0 w-24 h-auto drop-shadow-sm" alt="Signature">
                            @else
                                <div class="absolute bottom-4 left-0 w-24 border-b-2 border-slate-200"></div>
                            @endif

                            @if (!empty($tamponBase64))
                                <img src="{{ $tamponBase64 }}"
                                    class="absolute bottom-[-16px] left-16 w-44 h-44 opacity-85 mix-blend-multiply"
                                    alt="Tampon officiel">
                            @else
                                <div
                                    class="absolute bottom-[-16px] left-16 w-44 h-44 rounded-full border-2 border-dashed border-slate-200 flex items-center justify-center">
                                    <span
                                        class="text-[8px] font-bold uppercase tracking-wider text-slate-300 text-center leading-snug">Cachet<br>officiel</span>
                                </div>
                            @endif

                        </div>

                        <p style="font-size:8px;font-weight:700;text-transform:uppercase; letter-spacing:.15em;color:#94a3b8">Signature &amp; Cachet</p>
                    </div>

                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <div id="qrcode" data-url="https://usuel.savoirsvivants.fr/"
                            class="border border-slate-100 rounded-xl bg-white shadow-sm" style="padding:8px"></div>
                        <p
                            style="font-family:monospace;font-size:9px;font-weight:700;
                       color:#94a3b8;letter-spacing:.1em">
                            CERT-{{ str_pad($passation->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
