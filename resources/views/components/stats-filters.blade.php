@props([
    'availableAges' => [],
    'availableGenres' => [],
    'availableCsps' => [],
    'availableDiplomes' => [],
    'availableModes' => [],
    'labelsMap' => []
])

<div x-show="filterOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-x-2"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 -translate-x-2" x-cloak
    class="absolute top-0 left-0 z-20 w-68 bg-white border-r border-b border-gray-100 shadow-lg rounded-bl-2xl overflow-y-auto max-h-[70vh]">
    <div class="p-5 space-y-1">

        @if (!empty($availableAges))
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                    Âge
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                    @foreach ($availableAges as $val)
                        <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                            <input type="checkbox" wire:model.live="selectedAges" value="{{ $val }}" class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                            {{ $labelsMap[$val] ?? $val }}
                        </label>
                    @endforeach
                </div>
            </details>
            <div class="border-t border-gray-50 my-1"></div>
        @endif

        @if (!empty($availableGenres))
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                    Genre
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                    @foreach ($availableGenres as $val)
                        <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                            <input type="checkbox" wire:model.live="selectedGenres" value="{{ $val }}" class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                            {{ $labelsMap[$val] ?? $val }}
                        </label>
                    @endforeach
                </div>
            </details>
            <div class="border-t border-gray-50 my-1"></div>
        @endif

        @if (!empty($availableCsps))
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                    Catégorie Socio-Professionnelle
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                    @foreach ($availableCsps as $val)
                        <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                            <input type="checkbox" wire:model.live="selectedCsps" value="{{ $val }}" class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                            {{ $labelsMap[$val] ?? $val }}
                        </label>
                    @endforeach
                </div>
            </details>
            <div class="border-t border-gray-50 my-1"></div>
        @endif

        @if (!empty($availableDiplomes))
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                    Diplôme
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                    @foreach ($availableDiplomes as $val)
                        <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                            <input type="checkbox" wire:model.live="selectedDiplomes" value="{{ $val }}" class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                            {{ $labelsMap[$val] ?? $val }}
                        </label>
                    @endforeach
                </div>
            </details>
            @if(!empty($availableModes)) <div class="border-t border-gray-50 my-1"></div> @endif
        @endif

        @if (!empty($availableModes))
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer py-2.5 px-1 font-bold text-xs uppercase tracking-widest text-gray-400 hover:text-sv-blue list-none select-none transition-colors">
                    Mode de passation
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="pb-3 pt-1 flex flex-col gap-1.5 px-1">
                    @foreach ($availableModes as $val)
                        <label class="flex items-center gap-3 text-sm text-gray-600 cursor-pointer hover:text-sv-blue py-0.5 transition-colors">
                            <input type="checkbox" wire:model.live="selectedModes" value="{{ $val }}" class="w-3.5 h-3.5 text-sv-green rounded border-gray-300 focus:ring-sv-green">
                            @php
                                $nomMode = match ($val) {
                                    'fixe' => 'Mode fixe',
                                    'aleatoire' => 'Mode aléatoire',
                                    'semi_aleatoire' => 'Mode semi-aléatoire',
                                    'carre_latin' => 'Carré latin',
                                    default => ucfirst(str_replace('_', ' ', $val)),
                                };
                            @endphp
                            {{ $nomMode }}
                        </label>
                    @endforeach
                </div>
            </details>
        @endif
    </div>
</div>
