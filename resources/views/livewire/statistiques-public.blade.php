<section class="flex min-h-screen bg-gray-50">
    <div class="ml-64 flex-1 p-8">
        <div>
            <div class="font-sans text-[#1a2340]" x-data="statsHandler({ timeRange: '{{ $timeRange }}' })">
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
                    @if (auth()->user()->role === 'admin')
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
                                <svg class="w-3.5 h-3.5 transition-transform duration-200"
                                    :class="{ 'rotate-180': filterOpen }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
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
                            <div class="flex items-center gap-2"
                                @if ($timeRange !== 'Custom') style="display:none" @endif x-show="customVisible"
                                x-cloak>
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
                        @include('components.stats-filters', [
                            'availableAges' => $availableAges,
                            'availableGenres' => $availableGenres,
                            'availableCsps' => $availableCsps,
                            'availableDiplomes' => $availableDiplomes,
                            'labelsMap' => $labelsMap,
                        ])

                        <div class="w-full min-w-0 p-6 bg-gray-50/40 overflow-hidden">
                            <div class="mb-6 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-sv-green/10 flex items-center justify-center shrink-0">
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
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Genre
                                            </p>
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
                                            <p class="font-bold text-sv-blue text-sm mt-0.5">Typologie du public (CSP)
                                            </p>
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
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                            Performance</p>
                                        <p class="font-bold text-sv-blue text-sm mt-0.5">Score moyen par tranche d'âge
                                        </p>
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
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                            Compétences</p>
                                        <p class="font-bold text-sv-blue text-sm mt-0.5">Forces et difficultés par
                                            dimension
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

            <div id="chart-data" data-chart-data='@json($this->chartData)' hidden>
            </div>
        </div>
    </div>
</section>
