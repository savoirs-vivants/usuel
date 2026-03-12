<section class="flex min-h-screen bg-gray-50">
    <div class="ml-64 flex-1 p-8">
        <div>
            <div class="font-sans text-[#1a2340]" x-data="statsHandler({ timeRange: '{{ $timeRange }}' })">

                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Analytique</p>
                        <h1 class="font-mono font-bold text-3xl text-sv-blue">Statistiques</h1>
                        <p class="text-sm text-gray-400 mt-1">Analyse comportementale du public évalué.</p>
                    </div>
                    @livewire('export-comportementale-modal', [
                        'selectedAges' => $selectedAges,
                        'selectedGenres' => $selectedGenres,
                        'selectedCsps' => $selectedCsps,
                        'selectedDiplomes' => $selectedDiplomes,
                        'selectedModes' => $selectedModes,
                        'timeRange' => $timeRange,
                        'customStartDate' => $customStartDate,
                        'customEndDate' => $customEndDate,
                    ])
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

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
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

                        <div wire:ignore class="w-full min-w-0 p-6 bg-gray-50/40 overflow-hidden pb-0">

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
                                        <span id="totalPassations">{{ $this->trackingData['total_passations'] }}</span>
                                        passations
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                    <div class="flex items-center justify-between mb-5">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                                Hésitation</p>
                                            <p class="font-bold text-sv-blue text-sm mt-0.5">Top 5 — Temps avant 1er
                                                clic (ms)
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
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                                Incertitude
                                            </p>
                                            <p class="font-bold text-sv-blue text-sm mt-0.5">Top 5 — Taux de changement
                                                de
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
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Recherche
                                        </p>
                                        <p class="font-bold text-sv-blue text-sm mt-0.5">Effet de l'ordre d'apparition
                                        </p>
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
                        </div>
                    </div>
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
                                    @php
                                        $cols = [
                                            ['key' => 'num', 'label' => 'N°', 'align' => 'left'],
                                            ['key' => 'categorie', 'label' => 'Catégorie', 'align' => 'left'],
                                            ['key' => 'intitule', 'label' => 'Extrait', 'align' => 'left'],
                                            ['key' => 'avg_score', 'label' => 'Score moy.', 'align' => 'right'],
                                            [
                                                'key' => 'avg_temps',
                                                'label' => 'Temps moy. (ms)',
                                                'align' => 'right',
                                            ],
                                            [
                                                'key' => 'avg_latence',
                                                'label' => 'Latence moy. (ms)',
                                                'align' => 'right',
                                            ],
                                            ['key' => 'avg_clics', 'label' => 'Clics moy.', 'align' => 'right'],
                                            [
                                                'key' => 'avg_changements',
                                                'label' => 'Changements moy.',
                                                'align' => 'right',
                                            ],
                                            [
                                                'key' => 'avg_hors_cible',
                                                'label' => 'Hors-cible moy.',
                                                'align' => 'right',
                                            ],
                                            [
                                                'key' => 'avg_pauses',
                                                'label' => 'Pauses moy.',
                                                'align' => 'right',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($cols as $col)
                                        <th
                                            class="px-4 py-3 whitespace-nowrap {{ $col['align'] === 'right' ? 'text-right' : 'text-left' }}">
                                            <button wire:click="sortBy('{{ $col['key'] }}')"
                                                class="inline-flex items-center gap-1.5 cursor-pointer select-none font-bold text-sv-blue uppercase tracking-wider hover:text-sv-green transition-colors {{ $col['align'] === 'right' ? 'flex-row-reverse' : '' }}">
                                                <span>{{ $col['label'] }}</span>
                                                <span class="inline-flex flex-col gap-[2px] shrink-0">
                                                    <svg class="w-2 h-2 transition-colors {{ $sortField === $col['key'] && $sortDirection === 'desc' ? 'text-[#1a2340]' : 'text-gray-300' }}"
                                                        viewBox="0 0 8 5" fill="currentColor">
                                                        <path d="M4 0L8 5H0L4 0Z" />
                                                    </svg>
                                                    <svg class="w-2 h-2 transition-colors {{ $sortField === $col['key'] && $sortDirection === 'asc' ? 'text-[#1a2340]' : 'text-gray-300' }}"
                                                        viewBox="0 0 8 5" fill="currentColor">
                                                        <path d="M4 5L0 0H8L4 5Z" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-50">
                                @forelse ($this->trackingData['tableau'] as $row)
                                    <tr class="hover:bg-gray-50/80 transition-colors"
                                        style="border-bottom:1px solid #f9fafb">
                                        <td class="px-4 py-2.5 font-mono font-bold text-gray-400">
                                            {{ $row['num'] }}</td>
                                        <td class="px-4 py-2.5">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold"
                                                style="background:rgba(26,158,126,0.1);color:#1a9e7e">
                                                {{ $row['categorie'] ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5 text-gray-600 max-w-xs truncate"
                                            title="{{ $row['intitule'] ?? '' }}">
                                            {{ $row['intitule'] ?? '' }}
                                        </td>

                                        @if (($row['nb_occurrences'] ?? 0) == 0)
                                            <td colspan="7"
                                                class="px-4 py-2.5 text-center text-gray-300 text-xs italic">
                                                Aucune donnée</td>
                                        @else
                                            <td class="px-4 py-2.5 text-right font-mono font-bold"
                                                style="color: {{ ($row['avg_score'] ?? 0) > 0 ? '#1a9e7e' : (($row['avg_score'] ?? 0) < 0 ? '#f87171' : '#9ca3af') }}">
                                                {{ $row['avg_score'] ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                {{ isset($row['avg_temps']) ? number_format($row['avg_temps'], 0, ',', ' ') : '—' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                {{ isset($row['avg_latence']) ? number_format($row['avg_latence'], 0, ',', ' ') : '—' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                {{ $row['avg_clics'] ?? '—' }}</td>
                                            <td class="px-4 py-2.5 text-right font-mono"
                                                style="{{ ($row['avg_changements'] ?? 0) > 0.5 ? 'color:#f97316;font-weight:700' : 'color:#374151' }}">
                                                {{ $row['avg_changements'] ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                {{ $row['avg_hors_cible'] ?? '—' }}</td>
                                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">
                                                {{ $row['avg_pauses'] ?? '—' }}</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10"
                                            class="px-4 py-12 text-center text-gray-300 text-xs font-mono">
                                            Aucune donnée de tracking pour cette période.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="chart-data" data-tracking-data='@json($this->trackingData)' hidden>
                </div>
            </div>
        </div>
    </div>
</section>
