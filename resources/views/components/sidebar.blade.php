<aside class="w-64 shrink-0 flex flex-col fixed top-0 left-0 h-screen z-20 bg-sv-blue"
    style="box-shadow: 4px 0 24px rgba(0,0,0,0.18);">

    <div class="px-6 pt-7 pb-6">
        <span class="font-mono font-bold text-2xl text-white tracking-tight">Usuel</span>
        <div class="mt-1 h-0.5 w-8 rounded-full bg-sv-green"></div>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <p class="px-3 pt-1 pb-2 text-white/30 text-[10px] font-bold uppercase tracking-widest">Menu</p>

        <a href="{{ route('dashboard') }}"
            class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-200 relative
            {{ Route::is('dashboard')
                ? 'bg-sv-green/15 border border-sv-green/40 text-white'
                : 'text-white/60 hover:bg-white/8 hover:text-white border border-transparent' }}">
            @if (Route::is('dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-sv-green"></span>
            @endif
            <svg class="w-5 h-5 shrink-0 {{ Route::is('dashboard') ? 'text-sv-green' : 'text-white/40 group-hover:text-white/80' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z" />
            </svg>
            <span class="text-sm font-semibold">Dashboard</span>
        </a>

        <a href="{{ route('questionnaire.index') }}"
            class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-200 relative
            {{ Route::is('questionnaire.*')
                ? 'bg-sv-green/15 border border-sv-green/40 text-white'
                : 'text-white/60 hover:bg-white/8 hover:text-white border border-transparent' }}">
            @if (Route::is('questionnaire.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-sv-green"></span>
            @endif
            <svg class="w-5 h-5 shrink-0 {{ Route::is('questionnaire.*') ? 'text-sv-green' : 'text-white/40 group-hover:text-white/80' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="text-sm font-semibold">Questionnaire</span>
        </a>

        <a href="{{ route('passations') }}"
            class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-200 relative
            {{ Route::is('passations')
                ? 'bg-sv-green/15 border border-sv-green/40 text-white'
                : 'text-white/60 hover:bg-white/8 hover:text-white border border-transparent' }}">
            @if (Route::is('passations'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-sv-green"></span>
            @endif
            <svg class="w-5 h-5 shrink-0 {{ Route::is('passations') ? 'text-sv-green' : 'text-white/40 group-hover:text-white/80' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="text-sm font-semibold">Passations</span>
        </a>

        <a href="{{ route('statistiques.index') }}"
            class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-200 relative
            {{ Route::is('statistiques.*')
                ? 'bg-sv-green/15 border border-sv-green/40 text-white'
                : 'text-white/60 hover:bg-white/8 hover:text-white border border-transparent' }}">
            @if (Route::is('statistiques.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-sv-green"></span>
            @endif
            <svg class="w-5 h-5 shrink-0 {{ Route::is('statistiques.*') ? 'text-sv-green' : 'text-white/40 group-hover:text-white/80' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-sm font-semibold">Statistiques</span>
        </a>

        @if (Auth::user()->role !== 'travailleur')
            <a href="{{ route('backoffice') }}"
                class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-200 relative
            {{ Route::is('backoffice')
                ? 'bg-sv-green/15 border border-sv-green/40 text-white'
                : 'text-white/60 hover:bg-white/8 hover:text-white border border-transparent' }}">
                @if (Route::is('backoffice'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 rounded-r-full bg-sv-green"></span>
                @endif
                <svg class="w-5 h-5 shrink-0 {{ Route::is('backoffice') ? 'text-sv-green' : 'text-white/40 group-hover:text-white/80' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm font-semibold">Utilisateurs</span>
            </a>
        @endif

    </nav>
    <div class="px-4 pb-4 pt-3 space-y-1.5 border-t border-white/10">

        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-white/5 border border-white/8">
            <div class="w-8 h-8 rounded-lg bg-sv-green flex items-center justify-center shrink-0 text-white font-bold text-xs uppercase"
                style="box-shadow: 0 2px 8px rgba(22,152,124,0.4);">
                {{ substr(Auth::user()->firstname ?? (Auth::user()->email ?? 'U'), 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-semibold text-sm leading-tight truncate">
                    {{ Auth::user()->firstname }} {{ Auth::user()->name }}
                </p>
                <p class="text-white/40 text-xs capitalize mt-0.5">
                    {{ Auth::user()->role === 'travailleur' ? 'Travailleur social' : Auth::user()->role ?? 'Rôle' }}
                </p>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-sv-green text-white hover:bg-sv-green/80 transition-all duration-150 group">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span class="text-sm font-bold">Modifier le profil</span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm transition-all duration-150 group">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-150 group-hover:-translate-x-0.5"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Déconnexion
            </button>
        </form>

    </div>
</aside>
