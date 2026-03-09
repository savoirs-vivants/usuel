@extends('layouts.app')

@section('title', 'Usuel – Dashboard')

@section('content')

    @php
        $prenom = Auth::user()->firstname ?? (Auth::user()->name ?? 'vous');
        $heure = now()->hour;
        $salut = $heure < 12 ? 'Bonjour' : ($heure < 18 ? 'Bon après-midi' : 'Bonsoir');
    @endphp

    <section class="flex min-h-screen bg-gray-50">
        <div class="ml-64 flex-1">

    <div class="bg-white border-b border-gray-100 px-8 py-7">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium mb-1">{{ now()->isoFormat('dddd D MMMM YYYY') }}</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">{{ $salut }}, {{ $prenom }} 👋</h1>
                <p class="text-gray-400 text-sm mt-1">Voici un résumé de l'activité de votre plateforme.</p>
            </div>
            <a href="{{ route('questionnaire.run') ?? '#' }}"
                class="inline-flex items-center gap-2.5 bg-sv-green hover:bg-sv-green/90 text-white font-bold text-sm px-6 py-3.5 rounded-2xl shadow-lg shadow-sv-green/25 transition-all duration-150 hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle passation
            </a>
        </div>
    </div>

    <div class="px-8 py-8 space-y-6">

        <div class="grid grid-cols-3 gap-4">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ce mois-ci</p>
                    <div class="w-9 h-9 bg-sv-green/10 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-sv-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="font-mono font-bold text-5xl text-sv-blue leading-none mb-2">{{ $passationsMois }}</p>
                <p class="text-sm text-gray-400 font-medium">passations ce mois</p>
                @if ($evolutionMois !== null)
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-bold {{ $evolutionMois >= 0 ? 'text-sv-green' : 'text-red-400' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="{{ $evolutionMois >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                        </svg>
                        {{ abs($evolutionMois) }} par rapport au mois dernier
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bénéficiaires</p>
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-mono font-bold text-5xl text-sv-blue leading-none mb-2">{{ $totalBeneficiaires }}</p>
                <p class="text-sm text-gray-400 font-medium">personnes évaluées</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total</p>
                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-mono font-bold text-5xl text-sv-blue leading-none mb-2">{{ $totalPassations }}</p>
                <p class="text-sm text-gray-400 font-medium">passations au total</p>
            </div>

        </div>

        <div class="grid grid-cols-3 gap-4">

            <div class="col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="font-bold text-sv-blue text-sm mb-0.5">Activité des 6 derniers mois</p>
                <p class="text-xs text-gray-400 mb-5">Nombre de passations réalisées par mois</p>
                <div style="height: 170px;">
                    <canvas id="chartMois"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="font-bold text-sv-blue text-sm mb-0.5">Répartition des scores</p>
                <p class="text-xs text-gray-400 mb-5">Toutes les passations</p>
                @if ($totalPassations > 0)
                    <div style="height: 170px;">
                        <canvas id="chartScores"></canvas>
                    </div>
                @else
                    <div class="h-40 flex flex-col items-center justify-center gap-2 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-xs font-medium">Aucune donnée</p>
                    </div>
                @endif
            </div>

        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-sv-blue text-sm">Dernières passations</p>
                    <p class="text-xs text-gray-400 mt-0.5">Les 5 passations les plus récentes</p>
                </div>
                <a href="{{ route('passations') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-green hover:text-sv-green/70 transition-colors">
                    Voir tout
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if ($dernieresPassations->isEmpty())
                <div class="text-center py-14">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Aucune passation pour l'instant</p>
                    <p class="text-xs text-gray-300 mt-1">Les passations apparaîtront ici</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-50">
                            @if (auth()->user()->role == 'admin')
                                <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">ID</th>
                            @endif
                            @if (auth()->user()->role !== 'admin')
                                <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Bénéficiaire</th>
                            @endif
                            @if (auth()->user()->role === 'gestionnaire')
                                <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Travailleur social</th>
                            @endif
                            <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Date</th>
                            <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Score</th>
                            <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dernieresPassations as $p)
                            @php $s = $p->score_total ?? 0; @endphp
                            <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors">

                                @if (auth()->user()->role == 'admin')
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-lg">#{{ $p->id }}</span>
                                    </td>
                                @endif

                                @if (auth()->user()->role !== 'admin')
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-sv-blue/8 flex items-center justify-center shrink-0 font-bold text-sv-blue text-xs uppercase">
                                                {{ substr($p->beneficiaire->prenom ?? 'I', 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-gray-800 text-sm">
                                                {{ $p->beneficiaire->prenom ?? 'Inconnu' }} {{ $p->beneficiaire->nom ?? '' }}
                                            </span>
                                        </div>
                                    </td>
                                @endif

                                @if (auth()->user()->role === 'gestionnaire')
                                    <td class="px-6 py-4 text-gray-500 text-sm font-medium">
                                        {{ $p->user->firstname ?? '' }} {{ $p->user->name ?? '' }}
                                    </td>
                                @endif

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-500 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $p->created_at->format('d/m/Y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-mono font-bold text-sm {{ $s > 15 ? 'text-sv-green' : ($s >= 0 ? 'text-sv-blue' : 'text-red-400') }}">{{ $s }}</span>
                                        <span class="text-gray-300 text-xs">/30</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('questionnaire.result', $p->id) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-blue bg-sv-blue/5 hover:bg-sv-blue hover:text-white border border-sv-blue/15 rounded-xl px-3 py-1.5 transition-all duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Voir le résultat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const ctxBar = document.getElementById('chartMois');
            if (ctxBar) {
                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: @json($labelsBarChart),
                        datasets: [{
                            data: @json($dataBarChart),
                            backgroundColor: '#1a9e7e22',
                            borderColor: '#1a9e7e',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed.y} passation(s)`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    color: '#9ca3af'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    },
                                    color: '#9ca3af'
                                },
                                grid: {
                                    color: '#f3f4f6'
                                }
                            }
                        }
                    }
                });
            }

            const ctxDonut = document.getElementById('chartScores');
            if (ctxDonut) {
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Score faible (< 0)', 'Score moyen (0–15)', 'Score élevé (> 15)'],
                        datasets: [{
                            data: @json($repartitionScores),
                            backgroundColor: ['#ef444422', '#f59e0b22', '#1a9e7e22'],
                            borderColor: ['#ef4444', '#f59e0b', '#1a9e7e'],
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    padding: 12,
                                    boxWidth: 12,
                                    color: '#374151'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed} passation(s)`
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

@endsection
