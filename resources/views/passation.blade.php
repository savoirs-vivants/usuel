@extends('layouts.app')

@section('title', 'Usuel - Liste des passations')

@section('content')

    <section class="flex min-h-screen bg-gray-100">

        <div class="ml-60 flex-1 p-10">

            <div class="mb-8">
                <div>
                    <h1 class="font-mono font-bold text-3xl text-sv-blue">Passations</h1>
                    <p class="text-gray-400 text-sm mt-1">Gestion des passations de la plateforme</p>
                </div>
            </div>

            <div class="relative mb-6 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Rechercher une passation…" data-search="table-passations"
                    class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-700 transition-colors duration-200">
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if ($passations->isNotEmpty())
                    <table id="table-passations" class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">ID
                                </th>
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                    Travailleur social
                                </th>
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                    Date du test</th>
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                    Ordre des questions</th>
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                    Score</th>
                                <th class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($passations as $passation)
                                <tr class="hover:bg-gray-50/80 transition-colors duration-100" x-data="{ confirmDelete: false }">
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $passation->id }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $passation->user->name }}
                                        {{ $passation->user->firstname }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $passation->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $passation->mode_ordre }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $passation->score_total }}/30</td>
                                    <td class="px-6 py-4">
                                        <div x-show="!confirmDelete" class="flex items-center gap-2">
                                            <a href="{{ route('questionnaire.result', $passation->id) }}"
                                                class="inline-block text-xs font-bold text-sv-blue border-2 border-sv-blue/20 hover:border-sv-blue rounded-lg px-3 py-1.5 transition-colors text-center">
                                                Voir les détails
                                            </a>
                                            <button @click="confirmDelete = true"
                                                class="text-xs font-bold text-red-500 border-2 border-red-200 hover:border-red-400 rounded-lg px-3 py-1.5 transition-colors">
                                                Supprimer
                                            </button>
                                        </div>

                                        <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500 font-medium">Confirmer ?</span>
                                            <form action="{{ route('passation.destroy', $passation) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-lg px-3 py-1.5 transition-colors">
                                                    Oui, supprimer
                                                </button>
                                            </form>
                                            <button @click="confirmDelete = false"
                                                class="text-xs font-bold text-gray-500 border-2 border-gray-200 hover:border-gray-400 rounded-lg px-3 py-1.5 transition-colors">
                                                Annuler
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-16 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="font-mono text-sm">Aucune passation trouvée</p>
                    </div>
                @endif
            </div>
            <p class="text-xs text-gray-400 font-mono mt-4">Total : {{ $passations->count() }} passation(s)</p>
        </div>
    </section>

@endsection
