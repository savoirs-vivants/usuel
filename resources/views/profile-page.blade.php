@extends('layouts.app')

@section('title', 'Usuel - Mon profil')

@section('content')

<div class="ml-60 min-h-screen bg-[#f4f5f9] p-10">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 max-w-[1200px] mx-auto items-start">
        <main>
            <div class="mb-8">
                <h1 class="font-mono text-3xl font-bold text-[#1a2340] mb-2">Modifier mon profil</h1>
                <p class="text-gray-500 text-sm">Gérez vos informations personnelles et sécurisez votre compte.</p>
            </div>

            @livewire('edit-profile')
        </main>

        <aside class="bg-[#1a2340] rounded-[24px] px-[30px] py-[40px] sticky top-10 text-white shadow-[0_20px_40px_rgba(26,35,64,0.15)] overflow-hidden relative">

            <div class="absolute -top-[50px] -right-[50px] w-[150px] h-[150px] bg-[#16987C] rounded-full blur-[40px] opacity-30 pointer-events-none"></div>

            <div class="w-[88px] h-[88px] rounded-[24px] bg-gradient-to-br from-[#16987C] to-[#0d7a62] flex items-center justify-center font-mono text-[32px] font-bold text-white mb-6 shadow-[0_8px_24px_rgba(22,152,124,0.4)] relative z-10">
                {{ strtoupper(substr(Auth::user()->firstname ?? Auth::user()->name ?? 'U', 0, 1)) }}
            </div>

            <h2 class="text-2xl font-bold mb-3 relative z-10">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h2>

            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 px-[14px] py-1.5 rounded-full text-[11px] font-bold tracking-[1px] uppercase text-[#4ecca3] mb-8 relative z-10">
                <div class="w-2 h-2 rounded-full bg-[#4ecca3]"></div>
                {{ Auth::user()->role ?? '—' }}
            </div>

            <div class="flex flex-col gap-1 mb-5 relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-[1px] text-white/40">Adresse E-mail</span>
                <span class="text-[14px] font-medium text-white/90 break-words">{{ Auth::user()->email }}</span>
            </div>

            <div class="flex flex-col gap-1 mb-5 relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-[1px] text-white/40">Structure rattachée</span>
                <span class="text-[14px] font-medium text-white/90 break-words">{{ Auth::user()->structure ?? 'Aucune structure' }}</span>
            </div>

            <div class="h-[1px] bg-white/10 my-6 relative z-10"></div>

            <div class="flex flex-col gap-1 relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-[1px] text-white/40">Membre depuis</span>
                <span class="text-[14px] font-medium text-white/90 break-words">{{ Auth::user()->created_at?->translatedFormat('F Y') ?? '—' }}</span>
            </div>
        </aside>

    </div>
</div>

@endsection
