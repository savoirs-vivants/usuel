<div>
    <div class="font-sans text-[#1a2340]" x-data="{
        filterOpen: false,
        timeRange: '{{ $timeRange }}',
        customVisible: {{ $timeRange === 'Custom' ? 'true' : 'false' }},

        setRange(range) {
            this.timeRange = range;
            this.customVisible = (range === 'Custom');
        },

        init() {
            $wire.on('update-charts', (data) => {
                this.timeRange = '{{ $timeRange }}';
            });
        }
    }">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Analytique</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">Statistiques</h1>
                <p class="text-sm text-gray-400 mt-1">Vue d'ensemble du public évalué.</p>
            </div>
            @livewire('export-modal', [
                'selectedAges' => $selectedAges,
                'selectedGenres' => $selectedGenres,
                'selectedCsps' => $selectedCsps,
                'selectedDiplomes' => $selectedDiplomes,
                'timeRange' => $timeRange,
                'customStartDate' => $customStartDate,
                'customEndDate' => $customEndDate,
            ])
        </div>

        <div class="flex items-center gap-1 border-b border-gray-200 mb-6">
            <a href="{{ route('statistiques.index') }}"
                class="px-4 py-2.5 text-sm font-bold text-sv-green border-b-2 border-sv-green -mb-px transition-colors">
                Public
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('statistiques.comportementale') }}"
                class="px-4 py-2.5 text-sm font-semibold text-gray-400 border-b-2 border-transparent hover:text-sv-blue hover:border-gray-300 -mb-px transition-colors">
                Comportementale
            </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <button @click="filterOpen = !filterOpen"
                        class="flex items-center gap-2 text-sm font-bold text-sv-blue hover:text-sv-green transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filtres
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': filterOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="w-px h-4 bg-gray-200"></div>
                    <button wire:click="resetFilters" @click="setRange('A'); filterOpen = false;"
                        class="flex items-center gap-1.5 text-xs font-bold text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réinitialiser
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2" @if ($timeRange !== 'Custom') style="display:none" @endif
                        x-show="customVisible" x-cloak>
                        <input type="date" wire:model.live="customStartDate"
                            class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 outline-none focus:border-sv-green text-sv-blue font-medium">
                        <span class="text-gray-300 text-xs">→</span>
                        <input type="date" wire:model.live="customEndDate"
                            class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 outline-none focus:border-sv-green text-sv-blue font-medium">
                    </div>
                    <div class="bg-gray-100 p-1 rounded-xl flex items-center gap-0.5">
                        @foreach (['J' => 'Jour', 'M' => 'Mois', 'A' => 'Année', 'Custom' => 'Custom'] as $range => $label)
                            <button wire:click="setTimeRange('{{ $range }}')"
                                @click="setRange('{{ $range }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200
                                {{ $timeRange === $range ? 'bg-white shadow-sm text-sv-blue' : 'text-gray-400 hover:text-gray-600' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative flex">
                <div x-show="filterOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-2" x-cloak
                    class="absolute top-0 left-0 z-20 w-68 bg-white border-r border-b border-gray-100 shadow-lg rounded-bl-2xl overflow-y-auto">
                    <div class="p-5 space-y-1">

                        @if (!empty($availableAges))
                            <details class="group">
                                <summary
                                    class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                                    Âge
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                                    @foreach ($availableAges as $val)
                                        <label
                                            class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                                            <input type="checkbox" wire:model.live="selectedAges"
                                                value="{{ $val }}"
                                                class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                                            {{ $labelsMap[$val] ?? $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                            <div class="border-t border-gray-50 my-1"></div>
                        @endif

                        @if (!empty($availableGenres))
                            <details class="group">
                                <summary
                                    class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                                    Genre
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                                    @foreach ($availableGenres as $val)
                                        <label
                                            class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                                            <input type="checkbox" wire:model.live="selectedGenres"
                                                value="{{ $val }}"
                                                class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                                            {{ $labelsMap[$val] ?? $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                            <div class="border-t border-gray-50 my-1"></div>
                        @endif

                        @if (!empty($availableCsps))
                            <details class="group">
                                <summary
                                    class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                                    Catégorie Socio-Professionnelle
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                                    @foreach ($availableCsps as $val)
                                        <label
                                            class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                                            <input type="checkbox" wire:model.live="selectedCsps"
                                                value="{{ $val }}"
                                                class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                                            {{ $labelsMap[$val] ?? $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                            <div class="border-t border-gray-50 my-1"></div>
                        @endif

                        @if (!empty($availableDiplomes))
                            <details class="group">
                                <summary
                                    class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                                    Diplôme
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </summary>
                                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                                    @foreach ($availableDiplomes as $val)
                                        <label
                                            class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                                            <input type="checkbox" wire:model.live="selectedDiplomes"
                                                value="{{ $val }}"
                                                class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                                            {{ $labelsMap[$val] ?? $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        @endif

                    </div>
                </div>

                <div class="w-full min-w-0 p-6 bg-gray-50/40 overflow-hidden">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-sv-green/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-sv-green" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-mono font-bold text-sv-blue text-sm leading-none">
                                <span id="totalPassations">{{ $this->chartData['total_passations'] }}</span>
                                passations
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Genre</p>
                                    <p class="font-bold text-sv-blue text-sm mt-0.5">Répartition par genre</p>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative h-56 flex justify-center">
                                <canvas id="genreChart"></canvas>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        Socio-professionnel</p>
                                    <p class="font-bold text-sv-blue text-sm mt-0.5">Typologie du public (CSP)</p>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-sv-green/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-sv-green" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative h-56 flex justify-center">
                                <canvas id="cspChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Performance</p>
                                <p class="font-bold text-sv-blue text-sm mt-0.5">Score moyen par tranche d'âge</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative h-56">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Compétences</p>
                                <p class="font-bold text-sv-blue text-sm mt-0.5">Forces et difficultés par dimension
                                </p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative h-72 flex justify-center">
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

            function initCharts(data) {
                if (!data) return;

                if (ageChart) {
                    ageChart.destroy();
                    ageChart = null;
                }
                if (genreChart) {
                    genreChart.destroy();
                    genreChart = null;
                }
                if (cspChart) {
                    cspChart.destroy();
                    cspChart = null;
                }
                if (dimChart) {
                    dimChart.destroy();
                    dimChart = null;
                }

                const hasAgeData = data.age_labels && data.age_labels.length > 0;
                const hasGenreData = data.genre_labels && data.genre_labels.length > 0;
                const hasCspData = data.csp_labels && data.csp_labels.length > 0;
                const hasDimData = data.dim_labels && data.dim_labels.length > 0;

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
                                return context.raw >= 0 ?
                                    'rgba(26, 158, 126, 0.85)' :
                                    'rgba(239, 68, 68, 0.85)';
                            },
                            borderRadius: (context) => {
                                const v = context.raw;
                                return v >= 0 ? {

                                    topLeft: 6,
                                    topRight: 6,
                                    bottomLeft: 0,
                                    bottomRight: 0
                                } : {

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
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1a2340',
                                titleColor: '#ffffff',
                                bodyColor: '#9ca3af',
                                borderColor: '#374151',
                                borderWidth: 1,
                                padding: 10,
                                boxPadding: 6,
                                usePointStyle: true,
                                titleFont: {
                                    weight: '600'
                                },
                                callbacks: {
                                    label: (ctx) => ` Score: ${Number(ctx.raw).toFixed(1)}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawTicks: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9ca3af',
                                    padding: 10,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    autoSkip: false,
                                    maxRotation: 0,
                                    minRotation: 0
                                }
                            },
                            y: {
                                grid: {
                                    color: (ctx) => ctx.tick.value === 0 ? '#d1d5db' : '#f3f4f6',
                                    lineWidth: (ctx) => ctx.tick.value === 0 ? 2 : 1,
                                    drawTicks: false,
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9ca3af',
                                    padding: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
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
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    color: '#374151'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1a2340'
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
                                    padding: 12,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    color: '#374151'
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
                                beginAtZero: false,
                                min: 0,
                                max: 5,
                                ticks: {
                                    stepSize: 1,
                                    backdropColor: 'transparent',
                                    color: '#9ca3af',
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },

                                    callback: function(value) {
                                        return (value);
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.06)'
                                },
                                angleLines: {
                                    color: 'rgba(0,0,0,0.06)'
                                },
                                pointLabels: {
                                    font: {
                                        size: 12,
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
                                    size: 13
                                },
                                callbacks: {
                                    label: (ctx) => ` Score : ${(ctx.raw).toFixed(1)} / 5`
                                }
                            }
                        }
                    }
                });

                document.getElementById('totalPassations').textContent = data.total_passations;
            }

            initCharts(initialData);

            window.addEventListener('update-charts', function(event) {
                const payload = event.detail;
                const data = Array.isArray(payload) ? payload[0] : (payload.data ?? payload);
                initCharts(data);
            });
        });
    </script>
</div>
