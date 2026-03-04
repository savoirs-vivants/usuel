<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat – {{ $passation->beneficiaire->prenom }} {{ $passation->beneficiaire->nom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sv-blue':  '#1a2340',
                        'sv-green': '#1a9e7e',
                    },
                    fontFamily: {
                        sans: ['Space Grotesk', 'Inter'],
                        mono: ['Space Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page { size: A4 portrait; margin: 0; }
            body  { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>

@php
    $scores     = $passation->score ?? [];
    $scoreTotal = $passation->score_total ?? array_sum($scores);

    $labelsMap = [
        'Resilience' => 'Résilience',
        'EC'         => 'Esprit Critique',
        'CSDLEN'     => 'Comp. sociales',
        'CT'         => 'Comp. Technique',
        'TDLinfo'    => 'Trait. info',
        'CDC'        => 'Création',
    ];

    $circumference = 264;
    $dashoffset    = max(0, $circumference - ($circumference * (($scoreTotal + 30) / 60)));
@endphp

<body class="bg-gray-200 print:bg-white min-h-screen flex justify-center items-start p-8 font-sans">

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

    <div class="flex-1 mx-10 rounded-2xl overflow-hidden border border-gray-100 flex flex-col relative z-10">
        <div class="bg-sv-blue px-6 py-3 flex items-center justify-between shrink-0">
            <span class="text-white font-semibold text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-sv-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z
                             m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10
                             m-6 0a2 2 0 002 2h2a2 2 0 002-2
                             m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Profil de compétences
            </span>
            <span class="text-sv-green font-mono text-xs">
                {{ $passation->created_at->translatedFormat('d F Y') }}
            </span>
        </div>

        <div class="flex-1 flex items-center gap-6 p-5 bg-slate-50">
            <div class="relative shrink-0" style="width:120px; height:120px;">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#1a9e7e" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="264"
                            stroke-dashoffset="{{ $dashoffset }}"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-mono font-bold text-sv-blue text-2xl leading-none">
                        {{ $scoreTotal > 0 ? '+' : '' }}{{ $scoreTotal }}
                    </span>
                    <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider mt-0.5">/ 30 pts</span>
                </div>
            </div>

            <div class="flex-1 relative min-h-56">
                <canvas id="radarChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <p class="text-center text-gray-500 text-xs leading-relaxed px-16 mt-4 shrink-0 relative z-10">
        Ce document atteste que le bénéficiaire a démontré sa capacité à naviguer, évaluer et créer
        de l'information dans un environnement numérique, conformément au référentiel Usuel.
    </p>

    <div class="shrink-0 mx-10 pt-4 pb-6 mt-auto flex items-end justify-between relative z-10">
        <div>
            <p class="text-xs text-gray-500 font-medium mb-1">Évalué par l'organisme de formation</p>
            <div class="h-10 w-36 border-b-2 border-gray-300 mb-1.5"></div>
            <p class="text-xs text-gray-400 uppercase tracking-widest font-semibold">Signature &amp; Cachet</p>
        </div>
        <div class="flex flex-col items-end gap-1.5">
            <div id="qrcode" class="border border-gray-200 rounded-lg p-1.5 bg-white shadow-sm"></div>
            <p class="font-mono text-sv-blue text-xs font-bold">
                ID : CERT-{{ $passation->id }}
            </p>
        </div>
    </div>

</div>{{-- fin .a4 --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    new QRCode(document.getElementById('qrcode'), {
        text: '{{ route('welcome') }}',
        width: 80,
        height: 80,
        colorDark: '#1a2340',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });

    const rawScores = @json($scores);
    const labelsMap = @json($labelsMap);

    const keys   = Object.keys(rawScores);
    const labels = keys.map(k => labelsMap[k] || k);
    const values = keys.map(k => parseFloat(rawScores[k]));
    const data   = values.map(v => v + 5);

    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: 'rgba(26,158,126,0.15)',
                borderColor: '#1a9e7e',
                borderWidth: 2.5,
                pointBackgroundColor: '#1a2340',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 12 },
            scales: {
                r: {
                    min: 0, max: 10,
                    ticks: { display: false },
                    grid:       { color: 'rgba(0,0,0,0.06)' },
                    angleLines: { color: 'rgba(0,0,0,0.06)' },
                    pointLabels: {
                        font: { size: 10, family: 'Inter', weight: '600' },
                        color: '#1a2340',
                        padding: 8,
                    },
                }
            },
            plugins: {
                legend:  { display: false },
                tooltip: { enabled: false },
            },
            animation: {
                onComplete: () => {
                    if (new URLSearchParams(window.location.search).get('print') === '1') {
                        window.print();
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>
