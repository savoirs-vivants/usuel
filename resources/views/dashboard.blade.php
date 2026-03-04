@extends('layouts.app')

@section('title', 'Usuel – Dashboard')

@section('content')

    @php
        $prenom = Auth::user()->firstname ?? (Auth::user()->name ?? 'vous');
        $heure = now()->hour;
        $salut = $heure < 12 ? 'Bonjour' : ($heure < 18 ? 'Bon après-midi' : 'Bonsoir');
    @endphp

    <section class="flex min-h-screen bg-gray-100">
        <div class="ml-60 flex-1 p-8">

            <div class="flex items-start justify-between mb-8">
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-0.5">
                        {{ now()->isoFormat('dddd D MMMM YYYY') }}
                    </p>
                    <h1 class="font-mono font-bold text-3xl text-sv-blue">
                        {{ $salut }}, {{ $prenom }} 👋
                    </h1>
                    <p class="text-gray-400 text-sm mt-1">
                        Voici un résumé de l'activité de votre plateforme.
                    </p>
                </div>

                <a href="{{ route('questionnaire.run') ?? '#' }}"
                    class="flex items-center gap-2 bg-sv-green hover:opacity-90 transition-opacity text-white font-bold text-sm font-mono px-5 py-3 rounded-xl shadow-lg shadow-sv-green/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle passation
                </a>
            </div>

            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ce mois-ci</p>
                        <div class="w-9 h-9 bg-sv-blue/8 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-sv-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-mono font-bold text-4xl text-sv-blue leading-none">{{ $passationsMois }}</p>
                        <p class="text-sm text-gray-500 mt-1.5 leading-snug">passations réalisées ce mois</p>
                    </div>
                    @if ($evolutionMois !== null)
                        <div
                            class="flex items-center gap-1.5 text-xs font-semibold
                {{ $evolutionMois >= 0 ? 'text-sv-green' : 'text-red-400' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $evolutionMois >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                            </svg>
                            {{ abs($evolutionMois) }} par rapport au mois dernier
                        </div>
                    @endif
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bénéficiaires</p>
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                         M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                                         m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-mono font-bold text-4xl text-sv-blue leading-none">{{ $totalBeneficiaires }}</p>
                        <p class="text-sm text-gray-500 mt-1.5 leading-snug">personnes évaluées<br>au total</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total</p>
                        <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-mono font-bold text-4xl text-sv-blue leading-none">{{ $totalPassations }}</p>
                        <p class="text-sm text-gray-500 mt-1.5 leading-snug">passations réalisées<br>depuis le début</p>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">

                <div class="col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="font-bold text-sv-blue text-sm">Activité des 6 derniers mois</p>
                            <p class="text-xs text-gray-400 mt-0.5">Nombre de passations réalisées par mois</p>
                        </div>
                    </div>
                    <div style="height:160px;">
                        <canvas id="chartMois"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="mb-4">
                        <p class="font-bold text-sv-blue text-sm">Répartition des scores</p>
                        <p class="text-xs text-gray-400 mt-0.5">Toutes les passations</p>
                    </div>
                    @if ($totalPassations > 0)
                        <div style="height:160px;">
                            <canvas id="chartScores"></canvas>
                        </div>
                    @else
                        <div class="h-40 flex items-center justify-center text-gray-300 text-xs">
                            Aucune donnée pour l'instant
                        </div>
                    @endif
                </div>

            </div>

            <div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-sv-blue text-sm">Dernières passations</p>
                            <p class="text-xs text-gray-400 mt-0.5">Les 5 passations les plus récentes</p>
                        </div>
                        <a href="{{ route('passations') }}" class="text-xs font-bold text-sv-green hover:underline">
                            Voir tout →
                        </a>
                    </div>

                    @if ($dernieresPassations->isEmpty())
                        <div class="text-center py-12 text-gray-300">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            </svg>
                            <p class="text-xs font-mono">Aucune passation pour l'instant</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                        ID</th>
                                    <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                        Date</th>
                                    <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                        Score</th>
                                    <th class="text-left px-6 py-3 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                        Détail du résultat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($dernieresPassations as $p)
                                    @php $s = $p->score_total ?? 0; @endphp
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-3 font-mono text-xs text-gray-400">#{{ $p->id }}</td>
                                        <td class="px-6 py-3 text-xs text-gray-500">
                                            {{ $p->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-3">
                                            <span
                                                class="inline-flex items-center font-bold text-xs px-2 py-0.5 rounded-lg
                    {{ $s > 15 ? 'bg-sv-green/10 text-sv-green' : ($s >= 0 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-500') }}">
                                                {{ $s }} / 30
                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            <a href="{{ route('questionnaire.result', $p->id) }}"
                                                class="text-xs font-bold text-sv-blue hover:text-sv-green transition-colors">
                                                Voir →
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
