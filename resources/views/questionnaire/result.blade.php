@extends('layouts.app')
@section('title', 'Résultats')
@section('content')
    @php
        $scores    = $passation->score ?? [];
        $labelsMap = [
            'Resilience' => 'Résilience',
            'EC'         => 'Esprit Critique',
            'CSDLEN'     => 'Comportements sociaux',
            'CT'         => 'Comp. Technique',
            'TDLinfo'    => "Traitement de l'info",
            'CDC'        => 'Création de contenu',
        ];
        $iconsMap  = [
            'Resilience' => '🛡️',
            'EC'         => '🧠',
            'CSDLEN'     => '🤝',
            'CT'         => '⚙️',
            'TDLinfo'    => '🔍',
            'CDC'        => '✏️',
        ];
        $catColors = [
            'Resilience' => '#3b82f6',
            'EC'         => '#8b5cf6',
            'CSDLEN'     => '#ec4899',
            'CT'         => '#f97316',
            'TDLinfo'    => '#10b981',
            'CDC'        => '#f59e0b',
        ];
        $scoreTotal    = $passation->score_total;
        $maxTotal      = 30.0;
        $minTotal      = -30.0;
        $maxParCat     = 5.0;
        $scorePct      = round((($scoreTotal - $minTotal) / ($maxTotal - $minTotal)) * 100);
        $circumference = round($scorePct * 3.14159);
    @endphp

    <div class="h-screen flex flex-col dot-grid">
        <div class="res-header shrink-0 bg-white border-b border-gray-100 px-8 pt-4 pb-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                @if (auth()->user()->role !== 'admin')
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#1a9e7e] to-[#1a2340] flex items-center justify-center shadow-md shadow-[#1a9e7e]/20">
                    <span class="text-sm font-bold text-white">
                        {{ strtoupper(substr($passation->beneficiaire->prenom, 0, 1)) }}{{ strtoupper(substr($passation->beneficiaire->nom, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-[#1a2340] font-bold text-sm leading-tight">
                        {{ $passation->beneficiaire->prenom }} {{ $passation->beneficiaire->nom }}
                    </p>
                    <p class="text-gray-400 text-xs">{{ $passation->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                @endif

                <div class="h-7 w-px bg-gray-200 mx-1"></div>

                <div class="flex items-center gap-2 bg-[#1a9e7e]/8 border border-[#1a9e7e]/20 rounded-xl px-3 py-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#1a9e7e] animate-pulse"></div>
                    <span class="text-[#1a9e7e] text-xs font-bold uppercase tracking-widest">Test terminé</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('passation.certificat', $passation->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-[#1a9e7e]/40 text-gray-500 hover:text-[#1a9e7e] font-semibold px-4 py-2 rounded-xl transition-all duration-200 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Certificat
                </a>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] active:scale-95 text-white font-semibold px-4 py-2 rounded-xl transition-all duration-200 text-sm shadow-md shadow-[#1a9e7e]/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <div class="flex-1 flex gap-4 px-8 py-5 min-h-0">
            <div class="res-left w-48 shrink-0 flex flex-col gap-4">
                <div class="card rounded-2xl p-5 flex flex-col items-center flex-1 justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-[#1a9e7e]/4 via-transparent to-transparent pointer-events-none rounded-2xl"></div>

                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-5 relative z-10">Score global</p>

                    <div class="relative w-32 h-32 mb-4 z-10">
                        <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="#e5e7eb" stroke-width="12"/>
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="#1a9e7e" stroke-width="18"
                                stroke-linecap="round" opacity="0.12"
                                stroke-dasharray="{{ $circumference }} 314"/>
                            <circle cx="60" cy="60" r="50" fill="none"
                                stroke="url(#dialGradientLight)" stroke-width="12"
                                stroke-linecap="round"
                                class="res-dial"
                                style="stroke-dasharray: {{ $circumference }} 314"/>
                            <defs>
                                <linearGradient id="dialGradientLight" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%"   stop-color="#1a9e7e"/>
                                    <stop offset="100%" stop-color="#34d399"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="res-score text-3xl font-bold text-[#1a2340] tabular-nums">{{ $scoreTotal }}</span>
                            <span class="text-gray-400 text-[10px] font-semibold">/ {{ (int)$maxTotal }} pts</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="res-radar flex-1 card rounded-2xl p-6 flex flex-col min-w-0 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-bl from-[#1a9e7e]/6 to-transparent pointer-events-none rounded-2xl"></div>

                <div class="flex items-center justify-between mb-4 shrink-0 relative z-10">
                    <div>
                        <p class="shimmer-label font-bold text-lg">Profil de compétences</p>
                    </div>
                </div>

                <div class="flex-1 relative min-h-0">
                    <canvas id="radarChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <div class="res-right w-60 shrink-0 flex flex-col gap-2.5 overflow-y-auto">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1 shrink-0">Diagnostic</p>

                @foreach ($scores as $clé => $score)
                    @php
                        $score   = (float) $score;
                        $label   = $labelsMap[$clé] ?? $clé;
                        $icon    = $iconsMap[$clé] ?? '📊';
                        $color   = $catColors[$clé] ?? '#6b7280';
                        $barPct  = max(0, min(100, round((($score + $maxParCat) / ($maxParCat * 2)) * 100)));
                        $isPos   = $score >= 0;
                        $cardIdx = $loop->iteration;
                    @endphp
                    <div class="res-card-{{ $cardIdx }} card hover:shadow-md rounded-xl px-4 py-3 transition-all duration-200 group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-base leading-none">{{ $icon }}</span>
                                <span class="text-[#1a2340] text-xs font-semibold truncate">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-bold tabular-nums px-2 py-0.5 rounded-full shrink-0
                                {{ $isPos ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-500 border border-red-100' }}">
                                {{ $isPos ? '' : '' }}{{ $score }}
                            </span>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="res-bar h-full rounded-full group-hover:brightness-110 transition-all"
                                 style="width: {{ $barPct }}%; background: linear-gradient(90deg, {{ $color }}80, {{ $color }})">
                            </div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-gray-300 text-[10px]">-{{ (int)$maxParCat }}</span>
                            <span class="text-gray-300 text-[10px]">{{ (int)$maxParCat }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="chart-data" class="hidden"
             data-radar-scores='@json($scores)'
             data-radar-labels='@json($labelsMap)'
             data-radar-colors='@json($catColors)'>
        </div>
    </div>
@endsection
