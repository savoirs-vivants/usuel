@extends('layouts.app')

@section('title', 'Usuel - Tous les utilisateurs')

@section('content')

    <section class="flex min-h-screen bg-gray-50">
        <div class="ml-64 flex-1 p-8">

            <div class="mb-8">
                <div class="mb-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Administration</p>
                    <h1 class="font-mono font-bold text-3xl text-sv-blue">Utilisateurs</h1>
                    <p class="text-sm text-gray-400 mt-1">Gestion des comptes de la plateforme</p>
                </div>
                <div class="flex items-center gap-3">
                    @livewire('create-user')
                </div>
            </div>
            <div class="relative mb-6 max-w-md">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Rechercher un utilisateur…" data-search="table-users"
                    class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-2xl pl-12 pr-4 py-3 text-sm text-gray-700 font-medium shadow-sm transition-colors duration-200 placeholder-gray-300">
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if ($users->isNotEmpty())
                    <table id="table-users" class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th
                                    class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Nom</th>
                                <th
                                    class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Email</th>
                                <th
                                    class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Rôle</th>
                                <th
                                    class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Structure</th>
                                <th
                                    class="text-left px-6 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors duration-100"
                                    x-data="{ confirmDelete: false }">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 font-bold text-sm uppercase
                                            @if ($user->role === 'admin') bg-red-100 text-red-600
                                            @elseif($user->role === 'gestionnaire') bg-sv-blue/10 text-sv-blue
                                            @else bg-sv-green/10 text-sv-green @endif">
                                                {{ substr($user->firstname ?? $user->email, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-gray-800">
                                                {{ $user->firstname }} {{ $user->name ?? '' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-500 font-medium">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold border
                                        @if ($user->role === 'admin') bg-red-50 text-red-700 border-red-200
                                        @elseif($user->role === 'gestionnaire') bg-sv-blue/8 text-sv-blue border-sv-blue/20
                                        @else bg-sv-green/10 text-sv-green border-sv-green/25 @endif">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full
                                            @if ($user->role === 'admin') bg-red-500
                                            @elseif($user->role === 'gestionnaire') bg-sv-blue
                                            @else bg-sv-green @endif"></span>
                                            @if ($user->role === 'admin')
                                                Administrateur
                                            @elseif($user->role === 'gestionnaire')
                                                Gestionnaire
                                            @else
                                                Travailleur social
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($user->structure)
                                            <span
                                                class="inline-flex items-center gap-1.5 text-gray-600 font-medium text-xs bg-gray-100 px-2.5 py-1 rounded-lg">
                                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $user->structure }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 font-medium">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div x-show="!confirmDelete" class="flex items-center gap-2">
                                            <a href="{{ route('user.edit', $user) }}"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-blue bg-sv-blue/5 hover:bg-sv-blue hover:text-white border border-sv-blue/15 rounded-xl px-3 py-2 transition-all duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </a>
                                            <button @click="confirmDelete = true"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-500 hover:text-white border border-red-200 rounded-xl px-3 py-2 transition-all duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Supprimer
                                            </button>
                                        </div>

                                        <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400 font-semibold">Confirmer ?</span>
                                            <form action="{{ route('backoffice.destroy', $user) }}" method="POST"
                                                class="inline">
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="font-mono font-bold text-sv-blue text-sm">Aucun utilisateur trouvé</p>
                        <p class="text-gray-400 text-xs mt-1">Les comptes apparaîtront ici une fois créés</p>
                    </div>
                @endif
            </div>

        </div>
    </section>

@endsection
