@extends('layouts.app')

@section('title', 'Statistiques - ' . $user->firstname . ' ' . $user->name)

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <a href="{{ route('backoffice') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-sv-blue transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux utilisateurs
        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8 relative">
            <div class="px-8 pb-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-5">
                        <div class="mt-6 w-24 h-24 bg-white rounded-2xl p-1.5 shadow-lg shrink-0 relative z-10">
                            <div
                                class="w-full h-full bg-gradient-to-br from-[#222A60]/10 to-[#16987C]/10 rounded-xl flex items-center justify-center text-3xl font-black text-[#222A60]">
                                {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="mb-1 mt-3 sm:mt-0">
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $user->firstname }}
                                {{ $user->name }}</h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span
                                    class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg">{{ ucfirst($user->role) }}</span>
                                @if ($user->structure)
                                    <span
                                        class="px-2.5 py-1 bg-[#222A60]/5 text-[#222A60] border border-[#222A60]/10 text-xs font-bold rounded-lg">{{ $user->structure }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:mb-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-600">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div
                class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-[#222A60]/30 transition-colors">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-[#222A60]/5 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div class="w-12 h-12 bg-[#222A60]/10 text-[#222A60] rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest leading-tight">Tests<br>Évalués
                    </h3>
                </div>
                <p class="text-4xl font-black text-[#222A60] relative z-10">{{ $nbPassations }}</p>
            </div>

            <div
                class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-[#16987C]/30 transition-colors">
                <div
                    class="absolute -right-6 -top-6 w-24 h-24 bg-[#16987C]/5 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div class="w-12 h-12 bg-[#16987C]/10 text-[#16987C] rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest leading-tight">
                        Bénéficiaires<br>Accompagnés</h3>
                </div>
                <p class="text-4xl font-black text-[#16987C] relative z-10">{{ $nbBeneficiaires }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <h2 class="text-lg font-black text-gray-900">Historique d'évaluation (10 dernières)</h2>
                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-lg">Récents</span>
            </div>

            @if ($recentPassations->isEmpty())
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium text-sm">Cet utilisateur n'a enregistré aucune passation pour le
                        moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                                    Date</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                                    Bénéficiaire</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                                    Scénario</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                                    Modules Évalués</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($recentPassations as $passation)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-gray-100 flex flex-col items-center justify-center text-[#222A60]">
                                                <span
                                                    class="text-sm font-black leading-none">{{ \Carbon\Carbon::parse($passation->created_at)->format('d') }}</span>
                                                <span
                                                    class="text-[9px] font-bold uppercase">{{ \Carbon\Carbon::parse($passation->created_at)->translatedFormat('M') }}</span>
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-gray-400">{{ \Carbon\Carbon::parse($passation->created_at)->format('H:i') }}</span>
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            @if (Auth::user()->role === 'admin')
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-black shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-bold text-gray-900">Bénéficiaire
                                                    #{{ $passation->id_beneficiaire }}</span>
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black shrink-0">
                                                    {{ substr($passation->prenom, 0, 1) }}{{ substr($passation->nom, 0, 1) }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-900">{{ $passation->prenom }}
                                                    {{ $passation->nom }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-8 py-5">
                                        <span
                                            class="inline-flex items-center justify-center min-w-[32px] h-8 bg-indigo-50 text-indigo-600 text-sm font-black rounded-lg border border-indigo-100">
                                            {{ $passation->scenario ?? '?' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($passation->modules_array as $module)
                                                <span
                                                    class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[12px] font-bold rounded-md truncate max-w-[200px]"
                                                    title="{{ $module }}">
                                                    {{ str_replace('Bloc ', 'B', $module) }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">Aucun module</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
