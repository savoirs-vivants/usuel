@extends('layouts.app')

@section('title', 'Usuel - Tous les utilisateurs')

@section('content')

<section class="flex min-h-screen bg-gray-50"
    x-data="selectionTable({{ collect($users->items())->pluck('id')->toJson() }})">

    <div class="ml-64 flex-1 p-8">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Administration</p>
                <h1 class="font-mono font-bold text-3xl text-sv-blue">Utilisateurs</h1>
                <p class="text-sm text-gray-400 mt-1">Gestion des comptes de la plateforme</p>
            </div>
            <div class="flex items-center gap-3">
                @livewire('create-user')
            </div>
        </div>

        <div class="mb-4">
            <form action="{{ route('backoffice') }}" method="GET" id="search-form" class="w-full flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-1">
                    <div class="relative w-full max-w-sm">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nom, email..."
                            class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-2xl pl-12 pr-10 py-2.5 text-sm text-gray-700 font-medium shadow-sm transition-colors duration-200 placeholder-gray-300">

                        @if(!empty($search))
                            <a href="{{ route('backoffice', ['per_page' => $perPage, 'structure' => $structureFilter]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 bg-gray-50 rounded-full p-1 transition-colors">
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
                    @if(Auth::user()->role === 'admin')
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <select name="structure" onchange="this.form.submit()"
                            class="w-full bg-white border-2 border-gray-200 focus:border-sv-green outline-none rounded-2xl pl-10 pr-4 py-2.5 text-sm text-gray-700 font-medium shadow-sm transition-colors duration-200 cursor-pointer appearance-none">
                            <option value="">Toutes les structures</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure }}" {{ ($structureFilter == $structure) ? 'selected' : '' }}>{{ Str::limit($structure, 25) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Actions groupées --}}
                    <div x-show="selected.length > 0" x-cloak x-transition
                        class="flex items-center gap-3 bg-white border border-gray-200 rounded-2xl px-4 py-2 shadow-sm h-[44px]">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-sv-blue flex items-center justify-center">
                                <span class="text-white font-mono font-bold text-xs" x-text="selected.length"></span>
                            </div>
                            <span class="text-sm font-semibold text-gray-600 hidden sm:inline">sélectionné(s)</span>
                        </div>
                        <div class="w-px h-4 bg-gray-200"></div>
                        <button type="button" @click="selected = []" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">Annuler</button>
                        <button type="button" @click="confirmBulkDelete = true" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl px-3 py-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Supprimer</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 bg-white px-4 py-2 border-2 border-gray-200 rounded-2xl shadow-sm shrink-0 h-[44px]">
                        <label for="per_page" class="text-xs font-bold text-gray-400 uppercase tracking-wide hidden sm:inline">Par page : </label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="bg-transparent text-sm font-bold text-sv-blue outline-none cursor-pointer">
                            @foreach([5, 10, 25, 50, 100] as $val)
                                <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modale de suppression groupée --}}
        <div x-show="confirmBulkDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-sv-blue/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.outside="confirmBulkDelete = false">
                <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="font-mono font-bold text-sv-blue text-center text-lg mb-1">Confirmer la suppression</p>
                <p class="text-gray-400 text-sm text-center mb-6">
                    Vous allez supprimer <span class="font-bold text-sv-blue" x-text="selected.length"></span> compte(s). Cette action est irréversible.
                </p>
                <div class="flex gap-3">
                    <button @click="confirmBulkDelete = false" class="flex-1 py-2.5 rounded-xl font-bold text-sm text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">Annuler</button>
                    <button @click="submitBulkDelete('{{ route('backoffice.destroyMultiple') }}')" class="flex-1 py-2.5 rounded-xl font-bold text-sm text-white bg-red-500 hover:bg-red-600 transition-colors">Oui, supprimer</button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($users->isNotEmpty())
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-100">
                            <th class="px-5 py-4 bg-gray-50/70 w-10">
                                <button @click="toggleAll()"
                                    class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-150"
                                    :class="allSelected ? 'bg-sv-blue border-sv-blue' : (someSelected ? 'bg-sv-blue/20 border-sv-blue' : 'border-gray-300 bg-white')">
                                    <svg x-show="allSelected" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    <svg x-show="someSelected && !allSelected" class="w-3 h-3 text-sv-blue" fill="currentColor" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="2" rx="1" /></svg>
                                </button>
                            </th>
                            <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Nom</th>
                            <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Email</th>
                            <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Rôle</th>
                            <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Structure</th>
                            <th class="text-left px-5 py-4 font-bold text-sv-blue text-xs uppercase tracking-wider bg-gray-50/70">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-50 transition-colors duration-100"
                                :class="selected.includes({{ $user->id }}) ? 'bg-blue-50/60' : 'hover:bg-gray-50/60'"
                                x-data="{ confirmDelete: false }">
                                <td class="px-5 py-4">
                                    <button @click="toggle({{ $user->id }})"
                                        class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all"
                                        :class="selected.includes({{ $user->id }}) ? 'bg-sv-blue border-sv-blue' : 'border-gray-300 bg-white'">
                                        <svg x-show="selected.includes({{ $user->id }})" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 font-bold text-sm uppercase
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : ($user->role === 'gestionnaire' ? 'bg-sv-blue/10 text-sv-blue' : 'bg-sv-green/10 text-sv-green') }}">
                                            {{ substr($user->firstname ?? $user->email, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $user->firstname }} {{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-500 font-medium">{{ $user->email }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold border
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border-red-200' : ($user->role === 'gestionnaire' ? 'bg-sv-blue/8 text-sv-blue border-sv-blue/20' : 'bg-sv-green/10 text-sv-green border-sv-green/25') }}">
                                        {{ ucfirst($user->role === 'travailleur' ? 'Travailleur social' : $user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-gray-600 font-medium text-xs bg-gray-100 px-2.5 py-1 rounded-lg">
                                        {{ $user->structure ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div x-show="!confirmDelete" class="flex items-center gap-2">
                                        <a href="{{ route('user.edit', $user) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sv-blue bg-sv-blue/5 hover:bg-sv-blue hover:text-white border border-sv-blue/15 rounded-xl px-3 py-2 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Modifier
                                        </a>
                                        <button @click="confirmDelete = true" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 hover:bg-red-500 hover:text-white border border-red-200 rounded-xl px-3 py-2 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Supprimer
                                        </button>
                                    </div>
                                    <div x-show="confirmDelete" x-cloak class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 font-semibold">Confirmer ?</span>
                                        <form action="{{ route('backoffice.destroy', $user) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl px-3 py-2 transition-colors">Oui</button>
                                        </form>
                                        <button @click="confirmDelete = false" class="text-xs font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl px-3 py-2 transition-colors">Non</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($users->hasPages())
                    <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50">
                        {{ $users->links('components.pagination-fr') }}
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="font-mono font-bold text-sv-blue text-sm">Aucun utilisateur trouvé</p>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
