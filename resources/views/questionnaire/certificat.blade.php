@extends('layouts.print')

@section('title', 'Certificat')

@section('content')

@php
    use App\Services\OrientationService;

    $scores     = $passation->score ?? [];
    $scoreTotal = $passation->score_total ?? array_sum($scores);

    $parcours = $passation->scenario;
    if (!$parcours) {
        $passation->loadMissing('beneficiaire');
        OrientationService::compute($passation);
        $passation->refresh();
        $parcours = $passation->scenario;
    }

    $parcoursLabel = OrientationService::PARCOURS_LABELS[$parcours] ?? '–';
    $modules       = $passation->modules ?? OrientationService::MODULES[$parcours] ?? [];

    $phrases = [
        'A' => "Chaque grande maîtrise commence par un premier geste. Vous avez franchi cette étape essentielle : un accompagnement dédié vous attend pour poser, ensemble, les fondations d'une autonomie numérique solide et durable.",
        'B' => "Vous disposez déjà de repères numériques réels. Un accompagnement ciblé vous permettra de gagner pleinement en autonomie pour vos démarches administratives du quotidien — et de faire valoir vos droits avec confiance.",
        'C' => "Votre potentiel numérique est là, bien présent. Avec un parcours adapté, vous acquerrez les compétences concrètes qui font aujourd'hui la différence dans le monde professionnel.",
        'D' => "Vous naviguez avec aisance dans le monde numérique. Pour aller encore plus loin, affiner votre regard critique vous permettra d'évoluer en toute sécurité et de faire de chaque usage une force.",
        'E' => "Vos bases sont solides, votre engagement, évident. Il ne vous reste plus qu'un cap décisif à franchir : créer, partager, et laisser votre empreinte dans l'espace numérique.",
        'F' => "Vous avez construit, au fil du temps, une vraie culture numérique. Quelques ateliers soigneusement choisis vous permettront d'en faire une compétence pleinement affirmée et reconnue.",
        'G' => "Vous utilisez le numérique avec naturel et intuition. Un accompagnement ciblé transformera ces réflexes en véritable maîtrise, sur l'ensemble des usages qui comptent dans votre vie.",
        'H' => "Vous maîtrisez le numérique avec aisance et discernement. Ce niveau d'excellence est un atout précieux — une certification officielle pourrait en porter pleinement témoignage.",
    ];
    $phrase = $phrases[$parcours] ?? '';
@endphp

<button onclick="window.print()"
    class="no-print fixed top-4 right-4 z-50 flex items-center gap-2 bg-slate-900 hover:bg-emerald-600 text-white font-semibold text-sm px-5 py-2.5 rounded-full shadow-lg transition-all duration-300">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2
                 m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z
                 m8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
    </svg>
    Générer le PDF
</button>

<div class="mx-auto relative overflow-hidden flex flex-row shadow-2xl print:shadow-none print:m-0 bg-white"
     style="width:210mm; min-height:297mm; -webkit-print-color-adjust: exact; print-color-adjust: exact;">

    <div class="w-[75mm] shrink-0 bg-slate-900 text-white relative flex flex-col justify-between p-8 overflow-hidden z-10">

        <div class="absolute -top-20 -left-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 -right-10 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-0 w-full h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>

        <div class="relative z-10 flex-1 flex flex-col justify-center items-center text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-6">Niveau Atteint</p>

            <div class="relative flex items-center justify-center w-36 h-36 mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-emerald-500/20"></div>
                <div class="absolute inset-2 rounded-full border-2 border-emerald-400/60 border-dashed animate-[spin_60s_linear_infinite]"></div>
                <span class="text-7xl font-black text-white drop-shadow-lg" style="font-family: monospace;">{{ $parcours }}</span>
            </div>

            <h3 class="text-2xl font-bold text-white leading-tight mb-3">{{ $parcoursLabel }}</h3>
            <span class="text-xs bg-emerald-500/20 text-emerald-300 px-4 py-1.5 rounded-full border border-emerald-500/30 font-medium tracking-wide">
                {{ OrientationService::ORIENTATIONS[$parcours] ?? '' }}
            </span>

            <div class="mt-12 w-full bg-white/5 rounded-2xl p-6 border border-white/10 backdrop-blur-md shadow-xl">
                <p class="text-xs uppercase tracking-widest text-slate-400 mb-2">Score Global</p>
                <p class="text-5xl font-black text-white" style="font-family: monospace;">
                    {{ $scoreTotal }}<span class="text-xl text-slate-400 font-medium">/30</span>
                </p>
            </div>
        </div>

        <div class="relative z-10 mb-8">
            @if (!empty($modules))
                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 mb-4 text-center">Recommandations</p>
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach ($modules as $module)
                        <span class="text-xs font-medium bg-slate-800/80 text-slate-200 px-3 py-1.5 rounded-lg border border-slate-700 shadow-sm">
                            {{ $module }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="flex-1 relative bg-white px-12 py-12 flex flex-col overflow-hidden">

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[450px] font-black text-slate-50 opacity-60 pointer-events-none select-none z-0" style="font-family: monospace;">
            {{ $parcours }}
        </div>

        <div class="relative z-10 text-right mb-16">
            <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter mb-2">Usuel</h1>
            <p class="text-emerald-600 font-bold uppercase tracking-[0.2em] text-xs">Certificat de compétences</p>
            <div class="w-16 h-1.5 bg-emerald-500 ml-auto mt-4 rounded-full"></div>
        </div>

        <div class="relative z-10 mb-16 mt-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-4">Délivré avec succès à</p>

            @if (auth()->user()->role !== 'admin')
                <h2 class="text-5xl font-black text-slate-900 uppercase tracking-tight leading-none mb-2">
                    {{ $passation->beneficiaire->prenom }}<br>
                    <span class="text-emerald-600">{{ $passation->beneficiaire->nom }}</span>
                </h2>
            @else
                <div class="w-4/5 h-12 border-b-2 border-slate-200 mb-4"></div>
                <div class="w-3/5 h-12 border-b-2 border-slate-200"></div>
            @endif
        </div>

        <div class="relative z-10 bg-slate-50 border-l-4 border-emerald-500 p-8 rounded-r-2xl mb-auto shadow-sm">
            <svg class="w-10 h-10 text-emerald-200 absolute -top-5 -left-5 bg-white rounded-full p-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
            <p class="text-slate-700 font-medium text-xl leading-relaxed relative z-10 italic">
                {{ $phrase }}
            </p>
        </div>

        <div class="relative z-10 flex items-end justify-between pt-8 border-t border-slate-100">
            <div class="max-w-[240px]">
                <p class="text-[9px] text-slate-400 leading-relaxed mb-6">
                    Ce document atteste que le bénéficiaire a complété l'évaluation et obtenu le profil de compétences présenté, conformément au référentiel Usuel.
                </p>
                <div class="border-b-2 border-slate-200 w-full mb-2"></div>
                <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Signature & Cachet</p>
            </div>

            <div class="flex flex-col items-end gap-3">
                <div id="qrcode" data-url="https://usuel.savoirsvivants.fr/" class="border-2 border-slate-100 p-2 rounded-xl shadow-sm bg-white"></div>
                <p class="font-mono text-[10px] font-bold text-slate-400 tracking-[0.1em]">
                    ID: CERT-{{ str_pad($passation->id, 6, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
