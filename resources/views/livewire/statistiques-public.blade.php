<div class="p-8 font-sans text-[#1a2340]">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Statistiques</h1>
        <button
            class="bg-[#1a9e7e] hover:bg-[#158a6c] text-white px-6 py-2 rounded-lg font-semibold transition-colors shadow-sm">
            Exporter
        </button>
    </div>

    <div class="flex items-center gap-8 border-b border-gray-200 mb-8">
        <a href="#" class="pb-3 border-b-2 border-blue-600 text-blue-600 font-semibold text-sm">Public</a>
        <a href="#" class="pb-3 text-gray-500 hover:text-gray-800 font-medium text-sm">Comportementale</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-xl">
            <div class="flex items-center gap-6">
                <button id="toggleFilters"
                    class="flex items-center gap-2 font-bold text-lg hover:text-[#1a9e7e] transition-colors">
                    Filtres
                    <svg class="w-5 h-5 transition-transform duration-200" id="filterIcon" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <button wire:click="resetFilters"
                    class="text-sm text-red-500 hover:text-red-700 font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Réinitialiser
                </button>
            </div>

            <div class="flex items-center gap-4">
                <div id="customDateRange" class="flex items-center gap-2 mr-2" style="display: none;">
                    <input type="date" wire:model.live="customStartDate" id="customStartDate"
                        class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:border-[#1a9e7e]">
                    <span class="text-gray-400">à</span>
                    <input type="date" wire:model.live="customEndDate" id="customEndDate"
                        class="text-sm border border-gray-300 rounded-md px-2 py-1 outline-none focus:border-[#1a9e7e]">
                </div>

                <div class="bg-gray-100 p-1 rounded-lg flex items-center gap-1 font-semibold text-sm"
                    id="timeRangeButtons">
                    <button wire:click="setTimeRange('J')" data-range="J"
                        class="time-btn px-4 py-1.5 rounded-md transition-all duration-200 text-gray-500 hover:text-gray-800">J</button>
                    <button wire:click="setTimeRange('M')" data-range="M"
                        class="time-btn px-4 py-1.5 rounded-md transition-all duration-200 text-gray-500 hover:text-gray-800">M</button>
                    <button wire:click="setTimeRange('A')" data-range="A"
                        class="time-btn px-4 py-1.5 rounded-md transition-all duration-200 text-gray-500 hover:text-gray-800">A</button>
                    <button wire:click="setTimeRange('Custom')" data-range="Custom"
                        class="time-btn px-4 py-1.5 rounded-md transition-all duration-200 text-gray-500 hover:text-gray-800">Custom</button>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div id="filterPanel" class="w-full lg:w-72 border-r border-gray-100 p-5 shrink-0 bg-white">
                @if (!empty($availableAges))
                    <details class="group mb-4" open>
                        <summary
                            class="flex items-center justify-between cursor-pointer font-semibold text-sm list-none select-none text-gray-700 hover:text-sv-blue">
                            Âge <svg class="w-4 h-4 transition group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="pt-3 flex flex-col gap-2">
                            @foreach ($availableAges as $val)
                                <label
                                    class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                    <input type="checkbox" wire:model.live="selectedAges" value="{{ $val }}"
                                        class="age-checkbox w-4 h-4 text-[#1a9e7e] rounded border-gray-300 focus:ring-[#1a9e7e]">
                                    {{ $labelsMap[$val] ?? $val }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                    <hr class="border-gray-100 my-4">
                @endif

                @if (!empty($availableGenres))
                    <details class="group mb-4" open>
                        <summary
                            class="flex items-center justify-between cursor-pointer font-semibold text-sm list-none select-none text-gray-700 hover:text-sv-blue">
                            Genre <svg class="w-4 h-4 transition group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="pt-3 flex flex-col gap-2">
                            @foreach ($availableGenres as $val)
                                <label
                                    class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                    <input type="checkbox" wire:model.live="selectedGenres" value="{{ $val }}"
                                        class="genre-checkbox w-4 h-4 text-[#1a9e7e] rounded border-gray-300 focus:ring-[#1a9e7e]">
                                    {{ $labelsMap[$val] ?? $val }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                    <hr class="border-gray-100 my-4">
                @endif

                @if (!empty($availableCsps))
                    <details class="group mb-4">
                        <summary
                            class="flex items-center justify-between cursor-pointer font-semibold text-sm list-none select-none text-gray-700 hover:text-sv-blue">
                            CSP <svg class="w-4 h-4 transition group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="pt-3 flex flex-col gap-2">
                            @foreach ($availableCsps as $val)
                                <label
                                    class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                    <input type="checkbox" wire:model.live="selectedCsps" value="{{ $val }}"
                                        class="csp-checkbox w-4 h-4 text-[#1a9e7e] rounded border-gray-300 focus:ring-[#1a9e7e]">
                                    {{ $labelsMap[$val] ?? $val }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                    <hr class="border-gray-100 my-4">
                @endif

                @if (!empty($availableDiplomes))
                    <details class="group">
                        <summary
                            class="flex items-center justify-between cursor-pointer font-semibold text-sm list-none select-none text-gray-700 hover:text-sv-blue">
                            Diplôme <svg class="w-4 h-4 transition group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="pt-3 flex flex-col gap-2">
                            @foreach ($availableDiplomes as $val)
                                <label
                                    class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                    <input type="checkbox" wire:model.live="selectedDiplomes"
                                        value="{{ $val }}"
                                        class="diplome-checkbox w-4 h-4 text-[#1a9e7e] rounded border-gray-300 focus:ring-[#1a9e7e]">
                                    {{ $labelsMap[$val] ?? $val }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>

            <div class="flex-1 p-8 bg-gray-50/30 overflow-hidden">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Résultats (<span id="totalPassations">0</span>
                            tests)</h2>
                        <p class="text-sm text-gray-500">Filtré selon vos critères actuels.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            Répartition par Genre
                        </h3>
                        <div class="relative w-full h-64 flex justify-center">
                            <canvas id="genreChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1a9e7e]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Typologie du Public (CSP)
                        </h3>
                        <div class="relative w-full h-64 flex justify-center">
                            <canvas id="cspChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-8">
                    <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Score Moyen par Tranche d'Âge
                    </h3>
                    <div class="relative w-full h-64">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-gray-700 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Analyse des Forces et Difficultés par Dimension
                    </h3>
                    <div class="relative w-full h-80 flex justify-center">
                        <canvas id="dimChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let ageChart = null;
        let genreChart = null;
        let cspChart = null;
        let dimChart = null;

        const initialData = @js($this->chartData);

        const CHART_COLORS = {
            blue: 'rgb(54, 162, 235)',
            green: 'rgb(75, 192, 192)',
            grey: 'rgb(201, 203, 207)',
            orange: 'rgb(255, 159, 64)',
            purple: 'rgb(153, 102, 255)',
            red: 'rgb(255, 99, 132)',
            yellow: 'rgb(255, 205, 86)',
            teal: 'rgb(20, 184, 166)',
            indigo: 'rgb(99, 102, 241)',
            pink: 'rgb(236, 72, 153)',
            sky: 'rgb(14, 165, 233)',
        };

        const CHART_COLORS_ALPHA = {
            blue: 'rgba(54, 162, 235, 0.6)',
            green: 'rgba(75, 192, 192, 0.6)',
            grey: 'rgba(201, 203, 207, 0.6)',
            orange: 'rgba(255, 159, 64, 0.6)',
            purple: 'rgba(153, 102, 255, 0.6)',
            red: 'rgba(255, 99, 132, 0.6)',
            yellow: 'rgba(255, 205, 86, 0.6)',
            teal: 'rgba(20, 184, 166, 0.6)',
            indigo: 'rgba(99, 102, 241, 0.6)',
            pink: 'rgba(236, 72, 153, 0.6)',
            sky: 'rgba(14, 165, 233, 0.6)',
        };

        const pieColors = [CHART_COLORS.sky, CHART_COLORS.pink, CHART_COLORS.yellow, CHART_COLORS.purple];
        const doughnutColors = [CHART_COLORS.teal, CHART_COLORS.indigo, CHART_COLORS.orange, CHART_COLORS.red,
            CHART_COLORS.green, CHART_COLORS.blue
        ];

        function calculateMax(scores) {
            if (!scores || scores.length === 0) return 10;
            const max = Math.max(...scores);
            return max <= 10 ? 10 : Math.ceil(max / 5) * 5 + 5;
        }

        function initCharts(data) {
            if (!data) return;
            if (ageChart) ageChart.destroy();
            if (genreChart) genreChart.destroy();
            if (cspChart) cspChart.destroy();
            if (dimChart) dimChart.destroy();

            const hasAgeData = data.age_labels && data.age_labels.length > 0;
            const hasGenreData = data.genre_labels && data.genre_labels.length > 0;
            const hasCspData = data.csp_labels && data.csp_labels.length > 0;
            const hasDimData = data.dim_labels && data.dim_labels.length > 0;
            const ageMax = calculateMax(data.age_scores);

            const ctxAge = document.getElementById('ageChart').getContext('2d');

            ageChart = new Chart(ctxAge, {
                type: 'bar',
                data: {
                    labels: hasAgeData ? data.age_labels : ['Aucune donnée'],
                    datasets: [{
                        label: 'Score',
                        data: hasAgeData ? data.age_scores : [0],

                        backgroundColor: (context) => {
                            if (!hasAgeData) return CHART_COLORS_ALPHA.grey;
                            const chart = context.chart;
                            const {
                                ctx,
                                chartArea,
                                scales
                            } = chart;
                            if (!chartArea) return null;

                            const value = context.raw;
                            const yZero = scales.y.getPixelForValue(0);

                            if (value >= 0) {
                                const gradient = ctx.createLinearGradient(0, yZero, 0,
                                    chartArea.top);
                                gradient.addColorStop(0,
                                'rgba(26, 158, 126, 0.1)');
                                gradient.addColorStop(1,
                                'rgba(26, 158, 126, 1)');
                                return gradient;
                            } else {
                                const gradient = ctx.createLinearGradient(0, yZero, 0,
                                    chartArea.bottom);
                                gradient.addColorStop(0,
                                'rgba(239, 68, 68, 0.1)');
                                gradient.addColorStop(1,
                                'rgba(239, 68, 68, 1)');
                                return gradient;
                            }
                        },

                        borderRadius: (context) => {
                            const value = context.raw;
                            return value >= 0 ?
                                {
                                    topLeft: 6,
                                    topRight: 6,
                                    bottomLeft: 0,
                                    bottomRight: 0
                                } :
                                {
                                    topLeft: 0,
                                    topRight: 0,
                                    bottomLeft: 6,
                                    bottomRight: 6
                                };
                        },

                        borderSkipped: false,
                        barPercentage: 0.55,
                        categoryPercentage: 0.8,
                    }]
                },
                options: {
                    indexAxis: 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#09090b',
                            bodyColor: '#71717a',
                            borderColor: '#e4e4e7',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 6,
                            usePointStyle: true,
                            titleFont: {
                                weight: '600'
                            },
                            callbacks: {
                                label: (context) => ` Score: ${context.raw.toFixed(1)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawTicks: false,
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#71717a',
                                padding: 10,
                                font: {
                                    size: 12
                                },
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        },
                        y: {
                            grid: {
                                color: (context) => context.tick.value === 0 ? '#d4d4d8' : '#f4f4f5',
                                lineWidth: (context) => context.tick.value === 0 ? 2 : 1,
                                drawTicks: false,
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#71717a',
                                padding: 10,
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });

            const ctxGenre = document.getElementById('genreChart').getContext('2d');
            genreChart = new Chart(ctxGenre, {
                type: 'pie',
                data: {
                    labels: hasGenreData ? data.genre_labels : ['Aucune donnée'],
                    datasets: [{
                        data: hasGenreData ? data.genre_counts : [1],
                        backgroundColor: hasGenreData ? pieColors.slice(0, data.genre_labels
                            .length) : [CHART_COLORS_ALPHA.grey],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverOffset: 15,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 25,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1a2340',
                        }
                    }
                }
            });

            const ctxCsp = document.getElementById('cspChart').getContext('2d');
            cspChart = new Chart(ctxCsp, {
                type: 'doughnut',
                data: {
                    labels: hasCspData ? data.csp_labels : ['Aucune donnée'],
                    datasets: [{
                        data: hasCspData ? data.csp_counts : [1],
                        backgroundColor: hasCspData ? doughnutColors.slice(0, data.csp_labels
                            .length) : [CHART_COLORS_ALPHA.grey],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverOffset: 15,
                        cutout: '60%',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1a2340',
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.raw / total) * 100)
                                        .toFixed(1) : 0;
                                    return ` ${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            const ctxDim = document.getElementById('dimChart').getContext('2d');

            dimChart = new Chart(ctxDim, {
                type: 'radar',
                data: {
                    labels: hasDimData ? data.dim_labels : ['Chargement...'],
                    datasets: [{
                        label: 'Score Normalisé',
                        data: hasDimData ? data.dim_scores : [],
                        fill: true,
                        backgroundColor: CHART_COLORS_ALPHA.teal,
                        borderColor: CHART_COLORS.teal,
                        borderWidth: 2.5,
                        pointBackgroundColor: CHART_COLORS.teal,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: CHART_COLORS.teal,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            min: 0,
                            max: 5,
                            ticks: {
                                stepSize: 2,
                                backdropColor: 'transparent',
                                color: '#6b7280',
                                font: {
                                    weight: '500'
                                },
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.08)'
                            },
                            angleLines: {
                                color: 'rgba(0,0,0,0.08)'
                            },
                            pointLabels: {
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#374151'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1a2340',
                            titleFont: {
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14
                            },
                            callbacks: {
                                label: (context) => `Score: ${context.raw.toFixed(1)} / 10`
                            }
                        }
                    }
                }
            });

            document.getElementById('totalPassations').textContent = data.total_passations;
        }

        initCharts(initialData);

        const toggleBtn = document.getElementById('toggleFilters');
        const filterPanel = document.getElementById('filterPanel');
        const filterIcon = document.getElementById('filterIcon');

        toggleBtn.addEventListener('click', function() {
            filterPanel.style.display = filterPanel.style.display === 'none' ? 'block' : 'none';
            filterIcon.classList.toggle('rotate-180');
        });

        const timeRange = '{{ $timeRange }}';
        document.querySelectorAll('.time-btn').forEach(btn => {
            if (btn.dataset.range === timeRange) {
                btn.classList.add('bg-white', 'shadow-sm', 'text-[#1a2340]');
            }
        });

        if (timeRange === 'Custom') {
            document.getElementById('customDateRange').style.display = 'flex';
        }

        window.addEventListener('update-charts', function(event) {
            initCharts(event.detail.data);
        });
    });
</script>
