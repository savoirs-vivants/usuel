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
    class="no-print fixed top-4 right-4 z-50 flex items-center gap-2 bg-sv-blue hover:bg-sv-green text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-lg transition-colors duration-200">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2
                 m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z
                 m8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
    </svg>
    Imprimer / Enregistrer en PDF
</button>

<div class="bg-white relative overflow-hidden flex flex-col shadow-2xl"
     style="width:210mm; min-height:297mm;">

    <div class="h-1.5 shrink-0 bg-gradient-to-r from-sv-blue via-sv-green to-sv-blue"></div>
    <div class="absolute -top-16 -right-16 w-72 h-72 bg-sv-green/5 rounded-full pointer-events-none"></div>

    <header class="shrink-0 text-center px-12 pt-7 pb-4 relative z-10">
        <div class="flex items-center justify-center gap-2 mb-5">
            <div class="w-8 h-8 bg-sv-blue rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-sv-green" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 21h20L12 2zm0 4l7 13H5l7-13zm-1 5v4h2v-4h-2zm0 5v2h2v-2h-2z"/>
                </svg>
            </div>
            <span class="font-mono font-bold text-sv-blue tracking-widest text-sm uppercase">Usuel</span>
        </div>

        <p class="font-semibold text-sv-green uppercase tracking-[.2em] text-xs mb-2">✦ Certificat de compétences</p>
        <h1 class="font-extrabold text-sv-blue text-3xl uppercase tracking-tight leading-tight mb-1">
            Littératie Numérique
        </h1>
        <p class="text-gray-400 text-sm">Attestation d'évaluation des compétences numériques</p>
        <div class="mt-5 mb-1">
            <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold mb-2">Délivré à</p>
            @if (auth()->user()->role !== 'admin')
            <span class="inline-block font-bold text-sv-blue text-2xl bg-slate-50 border-b-4 border-sv-green px-6 py-1.5 rounded-xl">
                {{ $passation->beneficiaire->prenom }} {{ $passation->beneficiaire->nom }}
            </span>
            @endif
        </div>
    </header>

    <div class="shrink-0 mx-10 rounded-2xl overflow-hidden relative z-10"
         style="height:108mm; background:linear-gradient(135deg,#1a2340 0%,#132d4a 55%,#0d3d2e 100%);">

        <div style="position:absolute;width:180px;height:180px;right:-30px;top:-30px;
                    border-radius:50%;background:rgba(26,158,126,0.07);pointer-events:none"></div>
        <div style="position:absolute;width:100px;height:100px;left:-15px;bottom:-15px;
                    border-radius:50%;background:rgba(26,158,126,0.05);pointer-events:none"></div>

        <div style="position:relative;z-index:10;display:flex;flex-direction:column;
                    height:100%;padding:1.5rem 1.75rem;gap:1rem;">

            <div style="display:flex;align-items:center;gap:1.5rem;">

                <div style="text-align:center;min-width:72px;flex-shrink:0;">
                    <p style="font-size:8px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.18em;color:#1a9e7e;margin-bottom:2px">Score global</p>
                    <p style="font-family:monospace;font-weight:800;font-size:2.4rem;
                               line-height:1;color:white;letter-spacing:-1px">
                        {{ $scoreTotal > 0 ? '+' : '' }}{{ $scoreTotal }}
                    </p>
                    <p style="font-size:9px;font-weight:600;color:rgba(255,255,255,0.3)">/ 30 points</p>
                </div>

                <div style="width:1px;align-self:stretch;background:rgba(255,255,255,0.12);flex-shrink:0"></div>

                <div style="display:flex;align-items:center;gap:1rem;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;
                                 width:3rem;height:3rem;border-radius:50%;background:#1a9e7e;
                                 color:white;font-family:monospace;font-weight:800;font-size:1.4rem;
                                 box-shadow:0 0 0 5px rgba(26,158,126,0.18);flex-shrink:0">
                        {{ $parcours }}
                    </span>
                    <div>
                        <p style="font-size:8px;font-weight:700;text-transform:uppercase;
                                   letter-spacing:.18em;color:#1a9e7e;margin-bottom:3px">
                            Parcours d'orientation
                        </p>
                        <p style="font-size:1rem;font-weight:800;color:white;line-height:1.2;margin-bottom:5px">
                            {{ $parcoursLabel }}
                        </p>
                        <span style="font-size:10px;font-weight:600;padding:2px 10px;border-radius:999px;
                                     background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.55);
                                     border:1px solid rgba(255,255,255,0.12)">
                            {{ OrientationService::ORIENTATIONS[$parcours] ?? '' }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="border-left:3px solid #1a9e7e;padding-left:1.1rem;flex:1;">
                <p style="font-size:0.8rem;color:rgba(255,255,255,0.8);line-height:1.7;
                           font-style:italic;font-family:Georgia,serif;margin:0">
                    {{ $phrase }}
                </p>
            </div>

            @if (!empty($modules))
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:auto;">
                @foreach ($modules as $module)
                    <span style="font-size:10px;font-weight:600;padding:3px 12px;border-radius:999px;
                                 background:rgba(26,158,126,0.18);color:#34d399;
                                 border:1px solid rgba(26,158,126,0.35)">
                        {{ $module }}
                    </span>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    <p class="text-center text-gray-500 text-xs leading-relaxed px-16 mt-4 shrink-0 relative z-10">
        Ce document atteste que le bénéficiaire a complété l'évaluation de littératie numérique Usuel
        et a obtenu le profil de compétences présenté ci-dessus, conformément au référentiel Usuel.
    </p>

    <div class="shrink-0 mx-10 pt-4 pb-6 mt-auto flex items-end justify-between relative z-10">
        <div>
            <p class="text-xs text-gray-500 font-medium mb-1">Évalué par l'organisme de formation</p>
            <div class="h-10 w-36 border-b-2 border-gray-300 mb-1.5"></div>
            <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Signature &amp; Cachet</p>
        </div>
        <div class="flex flex-col items-end gap-1.5">
            <div id="qrcode" data-url="https://usuel.savoirsvivants.fr/" class="border border-gray-200 rounded-lg p-1.5 bg-white shadow-sm"></div>
            <p class="font-mono text-sv-blue text-xs font-bold">
                ID : CERT-{{ $passation->id }}
            </p>
        </div>
    </div>

</div>
@endsection
