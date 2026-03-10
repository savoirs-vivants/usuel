@extends('layouts.app')

@section('title', 'Usuel - Liste des passations')

@section('content')

<section class="flex min-h-screen bg-gray-50">
    <div class="ml-64 flex-1 p-8">

        <div class="mb-8">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Gestion</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">Passations</h1>
                <p class="text-sm text-gray-400 mt-1">Liste de toutes les passations enregistrées</p>
            </div>
        </div>

        <div x-data="{
            selected: [],
            allIds: {{ collect($passations->items())->pluck('id')->toJson() }},
            get allSelected() { return this.allIds.length > 0 && this.selected.length === this.allIds.length; },
            get someSelected() { return this.selected.length > 0 && !this.allSelected; },
            toggleAll() {
                this.selected = this.allSelected ? [] : [...this.allIds];
            },
            toggle(id) {
                if (this.selected.includes(id)) {
                    this.selected = this.selected.filter(i => i !== id);
                } else {
                    this.selected.push(id);
                }
            },
            confirmBulkDelete: false,
        }">

                <div class="mb-4">

                <form action="{{ route('passations') }}" method="GET" class="w-full flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <div class="flex items-center gap-2 w-full lg:max-w-md">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Rechercher un bénéficiaire"
                            class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-2xl pl-12 pr-10 py-2.5 text-sm text-gray-700 font-medium shadow-sm transition-colors duration-200 placeholder-gray-300">

                        @if(!empty($search))
                            <a href="{{ route('passations', ['per_page' => $perPage]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 bg-gray-50 rounded-full p-1 transition-colors" title="Effacer la recherche">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="bg-sv-blue hover:bg-sv-blue/90 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-sm transition-colors shrink-0">
                        Rechercher
                    </button>
                </div>
                <div class="flex items-center gap-4">

                <div x-show="selected.length > 0" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-center gap-3 bg-white border border-gray-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-sv-blue flex items-center justify-center">
                            <span class="text-white font-mono font-bold text-xs" x-text="selected.length"></span>
                        </div>
                        <span class="text-sm font-semibold text-gray-600">
                            sélectionnée<span x-show="selected.length > 1">s</span>
                        </span>
                    </div>
                    <div class="w-px h-4 bg-gray-200"></div>
                    <button type="button" @click="selected = []"
                        class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        Désélectionner
                    </button>
                    <button type="button" @click="confirmBulkDelete = true"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl px-3 py-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer la sélection
                    </button>
                </div>
                        <div class="flex items-center gap-2 bg-white px-4 py-2 border-2 border-gray-200 rounded-2xl shadow-sm shrink-0">
                            <label for="per_page" class="text-xs font-bold text-gray-400 uppercase tracking-wide">Nombres par page : </label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="bg-transparent text-sm font-bold text-sv-blue outline-none cursor-pointer">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div x-show="confirmBulkDelete" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-sv-blue/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <p class="font-mono font-bold text-sv-blue text-center text-lg mb-1">Confirmer la suppression</p>
                    <p class="text-gray-400 text-sm text-center mb-6">
                        Vous allez supprimer <span class="font-bold text-sv-blue" x-text="selected.length"></span>
                        passation<span x-show="selected.length > 1">s</span>. Cette action est irréversible.
                    </p>
                    <div class="flex gap-3">
                        <button @click="confirmBulkDelete = false"
                            class="flex-1 py-2.5 rounded-xl font-bold text-sm text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                        <form action="{{ route('passation.destroyMultiple') }}" method="POST" class="flex-1" id="bulk-delete-form">
                            @csrf
                            @method('DELETE')
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit"
                                class="w-full py-2.5 rounded-xl font-bold text-sm text-white bg-red-500 hover:bg-red-600 transition-colors">
                                Oui, supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if ($passations->isNotEmpty())
                    <table id="table-passations" class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-100">

                                <th class="px-5 py-4 bg-gray-50/70 w-10">
                                    <button @click="toggleAll()"
                                        class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-150 shrink-0"
                                        :class="allSelected ? 'bg-sv-blue border-sv-blue' : (someSelected ? 'bg-sv-blue/20 border-sv-blue' : 'border-gray-300 hover:border-sv-blue bg-white')">
                                        <svg x-show="allSelected" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <svg x-show="someSelected && !allSelected" class="w-3 h-3 text-sv-blue" fill="currentColor" viewBox="0 0 24 24">
                                            <rect x="4" y="11" width="16" height="2" rx="1"/>
                                        </svg>
                                    </button>
                                </th>

                                @if (auth()->user()->role == 'admin')
                                    <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">ID</th>
                                @endif
                                @if (auth()->user()->role !== 'admin')
                                    <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Bénéficiaire</th>
                                @endif
                                @if (auth()->user()->role === 'gestionnaire')
                                    <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Travailleur social</th>
                                @endif
                                <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Date du test</th>
                                <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Score</th>
                                <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($passations as $passation)
                                <tr class="border-b border-gray-50 transition-colors duration-100"
                                    :class="selected.includes({{ $passation->id }}) ? 'bg-blue-50/60' : 'hover:bg-gray-50/60'"
                                    x-data="{ confirmDelete: false }">

                                    <td class="px-5 py-4">
                                        <button @click="toggle({{ $passation->id }})"
                                            class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-150 shrink-0"
                                            :class="selected.includes({{ $passation->id }}) ? 'bg-sv-blue border-sv-blue' : 'border-gray-300 hover:border-sv-blue bg-white'">
                                            <svg x-show="selected.includes({{ $passation->id }})" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </td>

                                    @if (auth()->user()->role == 'admin')
                                        <td class="px-5 py-4">
                                            <span class="font-mono font-bold text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-lg">#{{ $passation->id }}</span>
                                        </td>
                                    @endif

                                    @if (auth()->user()->role !== 'admin')
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-sv-blue/10 flex items-center justify-center shrink-0 font-bold text-sv-blue text-xs uppercase">
                                                    {{ substr($passation->beneficiaire->prenom ?? 'I', 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-gray-800">
                                                    {{ $passation->beneficiaire->prenom ?? 'Inconnu' }}
                                                    {{ $passation->beneficiaire->nom ?? '' }}
                                                </span>
                                            </div>
                                        </td>
                                    @endif

                                    @if (auth()->user()->role === 'gestionnaire')
                                        <td class="px-5 py-4 text-gray-500 font-medium">
                                            {{ $passation->user->firstname ?? '' }} {{ $passation->user->name ?? '' }}
                                        </td>
                                    @endif

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium text-sm">{{ $passation->date ? $passation->date->format('d/m/Y') : '—' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @php $score = $passation->score_total ?? 0; @endphp
                                        <div class="flex items-center gap-2.5">
                                            <span class="font-mono font-bold text-sv-blue text-sm">{{ $score }}</span>
                                            <span class="text-gray-300 text-xs font-medium">/30</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div x-show="!confirmDelete" class="flex items-center gap-2">
                                            <a href="{{ route('questionnaire.result', $passation->id) }}"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-blue bg-sv-blue/5 hover:bg-sv-blue hover:text-white border border-sv-blue/15 rounded-xl px-3 py-2 transition-all duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Voir le résultat
                                            </a>
                                            <button @click="confirmDelete = true"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-500 hover:text-white border border-red-200 rounded-xl px-3 py-2 transition-all duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </div>

                                        <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400 font-semibold">Confirmer ?</span>
                                            <form action="{{ route('passation.destroy', $passation) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl px-3 py-2 transition-colors">Oui</button>
                                            </form>
                                            <button @click="confirmDelete = false"
                                                class="text-xs font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl px-3 py-2 transition-colors">
                                                Annuler
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($passations->hasPages())
                        <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50">
                            {{ $passations->links('components.pagination-fr') }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-20">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <p class="font-mono font-bold text-sv-blue text-sm">Aucune passation trouvée</p>
                        <p class="text-gray-400 text-xs mt-1">Les passations apparaîtront ici une fois créées</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

@endsection
