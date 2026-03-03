<div>
    <div class="font-sans text-[#1a2340]" x-data="{
        filterOpen: false,
        customVisible: {{ $timeRange === 'Custom' ? 'true' : 'false' }},

        setRange(range) {
            this.customVisible = (range === 'Custom');
        },
    }">

        <div class="flex items-start justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Analytique</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">Statistiques</h1>
                <p class="text-sm text-gray-400 mt-1">Analyse comportementale par question.</p>
            </div>
        </div>

        <div class="flex items-center gap-1 border-b border-gray-200 mb-6">
            <a href="{{ route('statistiques.index') }}"
                class="px-4 py-2.5 text-sm font-semibold text-gray-400 border-b-2 border-transparent hover:text-sv-blue hover:border-gray-300 -mb-px transition-colors">
                Public
            </a>
            <a href="{{ route('statistiques.comportementale') }}"
                class="px-4 py-2.5 text-sm font-bold text-sv-green border-b-2 border-sv-green -mb-px transition-colors">
                Comportementale
            </a>
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
                    class="absolute top-0 left-0 z-20 bg-white border-r border-b border-gray-100 shadow-lg rounded-bl-2xl overflow-y-auto">
                    <div class="p-5 space-y-1">

                        @if (!empty($availableAges))
                            <details class="group" open>
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
                            <details class="group" open>
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
                                <span id="totalPassations">{{ $this->trackingData['total_passations'] }}</span>
                                passations
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Hésitation</p>
                                    <p class="font-bold text-sv-blue text-sm mt-0.5">Top 5 — Temps avant 1er clic (ms)
                                    </p>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-cyan-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative h-52"><canvas id="latenceChart"></canvas></div>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Incertitude
                                    </p>
                                    <p class="font-bold text-sv-blue text-sm mt-0.5">Top 5 — Taux de changement de
                                        réponse</p>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative h-52"><canvas id="changementsChart"></canvas></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Recherche</p>
                                <p class="font-bold text-sv-blue text-sm mt-0.5">Effet de l'ordre d'apparition</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative h-56"><canvas id="ordreChart"></canvas></div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Données</p>
                                <p class="font-bold text-sv-blue text-sm mt-0.5">Tableau d'analyse par question</p>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">Moyennes sur toutes les passations
                                filtrées</span>
                        </div>
                        <div class="overflow-auto" style="max-height: 420px;">
                            <table class="w-full text-xs min-w-[900px]">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th
                                            class="text-left px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            N°</th>
                                        <th
                                            class="text-left px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Catégorie</th>
                                        <th
                                            class="text-left px-4 py-3 font-bold text-sv-blue uppercase tracking-wider">
                                            Extrait</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Score moy. (/1)</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Temps moy. (ms)</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Latence moy. (ms)</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Clics moy.</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Changements moy.</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Hors-cible moy.</th>
                                        <th
                                            class="text-right px-4 py-3 font-bold text-sv-blue uppercase tracking-wider whitespace-nowrap">
                                            Pauses moy.</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($this->trackingData['tableau'] as $row)
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-4 py-2.5 font-mono font-bold text-gray-400">
                                                {{ $row['num'] }}</td>
                                            <td class="px-4 py-2.5">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-lg bg-sv-green/10 text-sv-green font-bold text-xs">
                                                    {{ $row['categorie'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5 text-gray-600 max-w-xs truncate"
                                                title="{{ $row['intitule'] }}">
                                                {{ $row['intitule'] }}
                                            </td>
                                            @if ($row['nb_occurrences'] > 0)
                                                <td
                                                    class="px-4 py-2.5 text-right font-mono font-bold {{ isset($row['avg_score']) ? ($row['avg_score'] > 0 ? 'text-sv-green' : ($row['avg_score'] < 0 ? 'text-red-400' : 'text-gray-400')) : 'text-gray-300' }}">
                                                    {{ $row['avg_score'] ?? '—' }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                    {{ number_format($row['avg_temps']) }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                    {{ number_format($row['avg_latence']) }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                    {{ $row['avg_clics'] }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono">
                                                    <span
                                                        class="{{ $row['avg_changements'] > 0.5 ? 'text-orange-500 font-bold' : 'text-gray-700' }}">
                                                        {{ $row['avg_changements'] }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                    {{ $row['avg_hors_cible'] }}</td>
                                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                    {{ $row['avg_pauses'] }}</td>
                                            @else
                                                <td colspan="8"
                                                    class="px-4 py-2.5 text-center text-gray-300 text-xs italic">Aucune
                                                    donnée</td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11"
                                                class="px-4 py-12 text-center text-gray-300 text-xs font-mono">
                                                Aucune donnée de tracking pour cette période.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let latenceChart = null,
                changementsChart = null,
                ordreChart = null;
            const initialData = @js($this->trackingData);

            const CYAN = 'rgb(6,182,212)',
                CYAN_A = 'rgba(6,182,212,0.75)';
            const ORANGE = 'rgb(249,115,22)',
                ORANGE_A = 'rgba(249,115,22,0.75)';
            const SV_GREEN = 'rgb(26,158,126)';

            const tooltipBase = {
                backgroundColor: '#1a2340',
                titleColor: '#fff',
                bodyColor: '#9ca3af',
                borderColor: '#374151',
                borderWidth: 1,
                padding: 10,
                boxPadding: 6
            };
            const axisTicks = {
                color: '#9ca3af',
                font: {
                    size: 11
                },
                padding: 8
            };

            function initCharts(data) {
                if (!data) return;
                if (latenceChart) {
                    latenceChart.destroy();
                    latenceChart = null;
                }
                if (changementsChart) {
                    changementsChart.destroy();
                    changementsChart = null;
                }
                if (ordreChart) {
                    ordreChart.destroy();
                    ordreChart = null;
                }

                const hasL = data.top5_latence?.length > 0;
                latenceChart = new Chart(document.getElementById('latenceChart'), {
                    type: 'bar',
                    data: {
                        labels: hasL ? data.top5_latence.map(d => d.label) : ['Aucune donnée'],
                        datasets: [{
                            data: hasL ? data.top5_latence.map(d => d.value) : [0],
                            backgroundColor: CYAN_A,
                            borderColor: CYAN,
                            borderWidth: 1.5,
                            borderRadius: {
                                topLeft: 0,
                                topRight: 6,
                                bottomLeft: 0,
                                bottomRight: 6
                            },
                            borderSkipped: 'left',
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                ...tooltipBase,
                                callbacks: {
                                    label: c => ` ${c.raw} ms`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    ...axisTicks,
                                    callback: v => v + ' ms'
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: axisTicks
                            }
                        }
                    }
                });

                const hasC = data.top5_changements?.length > 0;
                changementsChart = new Chart(document.getElementById('changementsChart'), {
                    type: 'bar',
                    data: {
                        labels: hasC ? data.top5_changements.map(d => d.label) : ['Aucune donnée'],
                        datasets: [{
                            data: hasC ? data.top5_changements.map(d => d.value) : [0],
                            backgroundColor: ORANGE_A,
                            borderColor: ORANGE,
                            borderWidth: 1.5,
                            borderRadius: {
                                topLeft: 6,
                                topRight: 6,
                                bottomLeft: 0,
                                bottomRight: 0
                            },
                            borderSkipped: false,
                            barPercentage: 0.55
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
                                ...tooltipBase,
                                callbacks: {
                                    label: c => ` ${c.raw} changement(s) moy.`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: axisTicks
                            },
                            y: {
                                grid: {
                                    color: '#f3f4f6'
                                },
                                border: {
                                    display: false
                                },
                                ticks: axisTicks,
                                beginAtZero: true
                            }
                        }
                    }
                });

                const hasO = data.ordre_positions?.length > 0;
                ordreChart = new Chart(document.getElementById('ordreChart'), {
                    type: 'line',
                    data: {
                        labels: hasO ? data.ordre_positions.map(p => 'Pos. ' + p) : ['Aucune donnée'],
                        datasets: [{
                                label: 'Temps total moy. (ms)',
                                data: hasO ? data.ordre_temps : [0],
                                borderColor: SV_GREEN,
                                fill: false,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                borderWidth: 2
                            },
                            {
                                label: 'Latence moy. (ms)',
                                data: hasO ? data.ordre_latence : [0],
                                borderColor: CYAN,
                                fill: false,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                borderWidth: 2,
                                borderDash: [4, 3]
                            },
                            {
                                label: 'Changements moy.',
                                data: hasO ? data.ordre_changements : [0],
                                borderColor: ORANGE,
                                fill: false,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                borderWidth: 2,
                                borderDash: [2, 3],
                                yAxisID: 'y2'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 11
                                    },
                                    color: '#374151',
                                    padding: 16
                                }
                            },
                            tooltip: tooltipBase
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    ...axisTicks,
                                    maxTicksLimit: 15
                                }
                            },
                            y: {
                                grid: {
                                    color: '#f3f4f6'
                                },
                                border: {
                                    display: false
                                },
                                ticks: axisTicks,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'ms',
                                    color: '#9ca3af',
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            y2: {
                                position: 'right',
                                beginAtZero: true,
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    ...axisTicks,
                                    font: {
                                        size: 10
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'changements',
                                    color: '#9ca3af',
                                    font: {
                                        size: 10
                                    }
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
