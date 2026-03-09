@extends('layouts.app')

@section('title', 'Usuel - Liste des passations')

@section('content')

<section class="flex min-h-screen bg-gray-50">
    <div class="ml-64 flex-1 p-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Gestion</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">Passations</h1>
                <p class="text-sm text-gray-400 mt-1">Liste de toutes les passations enregistrées</p>
            </div>
            <div class="bg-sv-blue/5 border border-sv-blue/10 rounded-2xl px-5 py-3 text-center">
                <p class="font-mono font-bold text-2xl text-sv-blue">{{ $passations->count() }}</p>
                <p class="text-xs text-gray-400 font-semibold mt-0.5">passation(s)</p>
            </div>
        </div>

        <div class="relative mb-6 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Rechercher une passation…" data-search="table-passations"
                class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-2xl pl-12 pr-4 py-3 text-sm text-gray-700 font-medium shadow-sm transition-colors duration-200 placeholder-gray-300">
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($passations->isNotEmpty())
                <table id="table-passations" class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-100">
                            @if (auth()->user()->role == 'admin')
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    ID
                                </th>
                            @endif
                            @if (auth()->user()->role !== 'admin')
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Bénéficiaire
                                </th>
                            @endif
                            @if (auth()->user()->role === 'gestionnaire')
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Travailleur social
                                </th>
                            @endif
                            <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                Date du test
                            </th>
                            <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                Score
                            </th>
                            <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($passations as $passation)
                            <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors duration-100 group"
                                x-data="{ confirmDelete: false }">
                                @if (auth()->user()->role == 'admin')
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-gray-400 text-xs bg-gray-100 px-2 py-1 rounded-lg">
                                            #{{ $passation->id }}
                                        </span>
                                    </td>
                                @endif
                                @if (auth()->user()->role !== 'admin')
                                    <td class="px-6 py-4">
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
                                    <td class="px-6 py-4 text-gray-500 font-medium">
                                        {{ $passation->user->firstname ?? '' }} {{ $passation->user->name ?? '' }}
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-medium">
                                            {{ $passation->date ? $passation->date->format('d/m/Y') : '—' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @php $score = $passation->score_total ?? 0; @endphp
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-mono font-bold text-sv-blue text-sm">{{ $score }}</span>
                                        <span class="text-gray-300 text-xs font-medium">/30</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div x-show="!confirmDelete" class="flex items-center gap-2">
                                        <a href="{{ route('questionnaire.result', $passation->id) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-blue bg-sv-blue/5 hover:bg-sv-blue hover:text-white border border-sv-blue/15 rounded-xl px-3 py-2 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir
                                        </a>
                                        <button @click="confirmDelete = true"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-500 hover:text-white border border-red-200 rounded-xl px-3 py-2 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>

                                    <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 font-semibold">Confirmer ?</span>
                                        <form action="{{ route('passation.destroy', $passation) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl px-3 py-2 transition-colors">
                                                Oui
                                            </button>
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

            @else
                <div class="text-center py-20">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <p class="font-mono font-bold text-sv-blue text-sm">Aucune passation trouvée</p>
                    <p class="text-gray-400 text-xs mt-1">Les passations apparaîtront ici une fois créées</p>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
