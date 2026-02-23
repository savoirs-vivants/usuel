{{-- resources/views/questionnaire/result.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats – Littératie numérique</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">

@php
    $scores = $passation->score ?? [];

    $labelsMap = [
        'Résilience' => 'Résilience',
        'EC'         => 'Esprit Critique',
        'CSDLEN'     => 'Comportements sociaux',
        'CT'         => 'Compétence technique',
        'TDLinfo'    => "Traitement de l'info",
        'CDC'        => 'Création de contenu',
    ];

    $scoreTotal   = $passation->score_total;
    $maxParCat    =  5.0;
    $minParCat    = -5.0;
    $maxTotal     = 30.0;
    $minTotal     = -30.0;

    $scorePct = round((($scoreTotal - $minTotal) / ($maxTotal - $minTotal)) * 100);

    $catColors = [
        'Résilience' => '#3b82f6',
        'EC'         => '#8b5cf6',
        'CSDLEN'     => '#ec4899',
        'CT'         => '#f97316',
        'TDLinfo'    => '#10b981',
        'CDC'        => '#f59e0b',
    ];
@endphp

<div class="min-h-screen py-12 px-6">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-10">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-2">Test terminé</p>
            <h1 class="text-3xl font-bold text-[#1a2340] mb-1">
                Bravo {{ $passation->beneficiaire->prenom }} !
            </h1>
            <p class="text-gray-400 text-sm">
                Voici le détail de vos résultats sur la littératie numérique.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center justify-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-6">Score global</p>

                <div class="relative w-44 h-44 mb-6">
                    <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                        <circle cx="60" cy="60" r="50"
                                fill="none" stroke="#e5e7eb" stroke-width="12"/>
                        <circle cx="60" cy="60" r="50"
                                fill="none"
                                stroke="{{ $scorePct >= 60 ? '#1a9e7e' : ($scorePct >= 40 ? '#f59e0b' : '#ef4444') }}"
                                stroke-width="12"
                                stroke-linecap="round"
                                stroke-dasharray="{{ round($scorePct * 3.14159) }} 314"
                                class="transition-all duration-1000"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-[#1a2340]">
                            {{ $scoreTotal > 0 ? '+' : '' }}{{ $scoreTotal }}
                        </span>
                        <span class="text-xs text-gray-400">/ {{ $maxTotal }} pts</span>
                    </div>
                </div>

                @if($passation->consentement_recherche)
                <p class="mt-4 text-xs text-gray-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Données partagées pour la recherche
                </p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4 text-center">
                    Profil par compétence
                </p>
                <div class="relative h-64">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-6">
                Détail par compétence
            </p>

            <div class="space-y-5">
                @foreach($scores as $clé => $score)
                @php
                    $score   = (float) $score;
                    $label   = $labelsMap[$clé] ?? $clé;
                    $color   = $catColors[$clé] ?? '#6b7280';
                    $pct     = max(0, min(100, round((($score - $minParCat) / ($maxParCat - $minParCat)) * 100)));
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-[#1a2340]">{{ $label }}</span>
                        <span class="text-sm font-bold tabular-nums {{ $score >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $score > 0 ? '+' : '' }}{{ $score }} pts
                        </span>
                    </div>
                    <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="width: {{ $pct }}%; background-color: {{ $color }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('questionnaire.index') }}"
               class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au questionnaire
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const rawScores  = @json($scores);
    const labelsMap  = @json($labelsMap);
    const catColors  = @json($catColors);

    const keys    = Object.keys(rawScores);
    const labels  = keys.map(k => labelsMap[k] ?? k);
    const values  = keys.map(k => parseFloat(rawScores[k]));
    const colors  = keys.map(k => catColors[k] ?? '#6b7280');

    const normalized = values.map(v => Math.round((v + 5) * 10) / 10);

    const ctx = document.getElementById('radarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Score',
                data: normalized,
                backgroundColor: 'rgba(26, 158, 126, 0.15)',
                borderColor: '#1a9e7e',
                borderWidth: 2.5,
                pointBackgroundColor: colors,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0,
                    max: 10,
                    ticks: {
                        stepSize: 2,
                        callback: val => (val - 5) >= 0 ? `+${val - 5}` : `${val - 5}`,
                        font: { size: 10 },
                        color: '#9ca3af',
                        backdropColor: 'transparent',
                    },
                    grid:        { color: '#e5e7eb' },
                    angleLines:  { color: '#e5e7eb' },
                    pointLabels: {
                        font: { size: 11, weight: '600' },
                        color: '#374151',
                    },
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const real = values[ctx.dataIndex];
                            return ` ${real > 0 ? '+' : ''}${real} pts`;
                        }
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>
