<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats – {{ $passation->beneficiaire->nom }} {{ $passation->beneficiaire->prenom }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased h-screen flex flex-col">

    @php
        $scores = $passation->score ?? [];

        $labelsMap = [
            'Resilience' => 'Résilience',
            'EC' => 'Esprit Critique',
            'CSDLEN' => 'Comportements sociaux',
            'CT' => 'Comp. Technique',
            'TDLinfo' => "Traitement de l'info",
            'CDC' => 'Création de contenu',
        ];

        $iconsMap = [
            'Resilience' => '🛡️',
            'EC' => '🧠',
            'CSDLEN' => '🤝',
            'CT' => '⚙️',
            'TDLinfo' => '🔍',
            'CDC' => '✏️',
        ];

        $catColors = [
            'Resilience' => '#3b82f6',
            'EC' => '#8b5cf6',
            'CSDLEN' => '#ec4899',
            'CT' => '#f97316',
            'TDLinfo' => '#10b981',
            'CDC' => '#f59e0b',
        ];

        $scoreTotal = $passation->score_total;
        $maxTotal = 30.0;
        $minTotal = -30.0;
        $maxParCat = 5.0;
        $scorePct = round((($scoreTotal - $minTotal) / ($maxTotal - $minTotal)) * 100);
    @endphp

    <div class="px-8 pt-5 pb-3 flex items-center justify-between shrink-0">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e]">Test terminé</p>
        </div>

        <a href="{{ route('passation.certificat', $passation->id) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-white border-2 border-[#1a9e7e] text-[#1a9e7e] hover:bg-[#1a9e7e] hover:text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Imprimer le certificat
        </a>
        <a href="{{ route('passations') }}"
            class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Retour
        </a>
    </div>

    <div class="flex-1 flex gap-5 px-8 pb-6 min-h-0">

        <div class="w-56 shrink-0 flex flex-col gap-3">

            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-[#1a9e7e]/10 flex items-center justify-center mb-3">
                    <span class="text-xl font-bold text-[#1a9e7e]">
                        {{ strtoupper(substr($passation->beneficiaire->prenom, 0, 1)) }}{{ strtoupper(substr($passation->beneficiaire->nom, 0, 1)) }}
                    </span>
                </div>
                <p class="font-bold text-[#1a2340] text-sm leading-tight">
                    {{ $passation->beneficiaire->prenom }} {{ $passation->beneficiaire->nom }}
                </p>
                <p class="text-[11px] text-gray-400 mt-0.5">
                    {{ $passation->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col items-center flex-1 justify-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Score global</p>

                <div class="relative w-28 h-28 mb-3">
                    <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb"
                            stroke-width="14" />
                        <circle cx="60" cy="60" r="50" fill="none" stroke="blue" stroke-width="14"
                            stroke-linecap="round" stroke-dasharray="{{ round($scorePct * 3.14159) }} 314" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-[#1a2340]">{{ $scoreTotal }}</span>
                        <span class="text-[10px] text-gray-400">/ {{ (int) $maxTotal }} pts</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col min-w-0">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4 text-center">Profil de
                compétences</p>
            <div class="flex-1 relative min-h-0">
                <canvas id="radarChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="w-64 shrink-0 flex flex-col gap-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Diagnostic</p>

            @foreach ($scores as $clé => $score)
                @php
                    $score = (float) $score;
                    $label = $labelsMap[$clé] ?? $clé;
                    $icon = $iconsMap[$clé] ?? '📊';
                    $color = $catColors[$clé] ?? '#6b7280';
                    $barPct = max(0, min(100, round((($score + $maxParCat) / ($maxParCat * 2)) * 100)));
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex items-center gap-3">
                    <span class="text-xl leading-none shrink-0">{{ $icon }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-[#1a2340] truncate">{{ $label }}</span>
                            <span
                                class="text-xs font-bold tabular-nums ml-1 shrink-0 {{ $score >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $score }}
                            </span>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-1.5">
                            <div class="h-full rounded-full"
                                style="width: {{ $barPct }}%; background-color: {{ $color }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const rawScores = @json($scores);
            const labelsMap = @json($labelsMap);
            const catColors = @json($catColors);

            const keys = Object.keys(rawScores);
            const labels = keys.map(k => labelsMap[k] ?? k);
            const values = keys.map(k => parseFloat(rawScores[k]));
            const colors = keys.map(k => catColors[k] ?? '#6b7280');
            const normalized = values.map(v => Math.round((v + 5) * 10) / 10);

            const ctx = document.getElementById('radarChart').getContext('2d');
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Score',
                        data: normalized,
                        backgroundColor: 'rgba(26, 158, 126, 0.12)',
                        borderColor: '#1a9e7e',
                        borderWidth: 2.5,
                        pointBackgroundColor: colors,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
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
                                font: {
                                    size: 10
                                },
                                color: '#9ca3af',
                                backdropColor: 'transparent',
                            },
                            grid: {
                                color: '#e5e7eb'
                            },
                            angleLines: {
                                color: '#d1d5db'
                            },
                            pointLabels: {
                                font: {
                                    size: 12,
                                    weight: '700'
                                },
                                color: '#374151',
                            },
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
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
