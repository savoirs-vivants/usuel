<aside class="w-60 shrink-0 bg-sv-blue flex flex-col fixed top-0 left-0 h-screen z-20">

    <div class="px-6 py-6 border-b border-white/10">
        <span class="font-mono font-bold text-2xl text-white tracking-tight">Usuel</span>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1">

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150 group
            {{ Route::is('dashboard') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z" />
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="{{ route('questionnaire.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150
            {{ Route::is('questionnaire.*') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="text-sm">Questionnaire</span>
        </a>

        <a href="{{ route('passations') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150
            {{ Route::is('passations') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="text-sm">Passations</span>
        </a>

        <a href="{{ route('certification') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150
            {{ Route::is('certification') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span class="text-sm">Certifications</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150
            {{ Route::is('statistiques.*') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-sm">Statistiques</span>
        </a>

        <a href="{{ route('backoffice') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-150
            {{ Route::is('backoffice') ? 'bg-sv-green text-white shadow-lg shadow-sv-green/20 font-bold' : 'text-white/70 hover:bg-white/10 hover:text-white font-semibold' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm">Utilisateurs</span>
        </a>

        <div class="mt-auto pt-4 border-t border-white/10">
            <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition-all duration-150 group">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center shrink-0 uppercase text-white font-bold text-xs">
                    {{ substr(Auth::user()->email ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 text-left overflow-hidden">
                    <p class="text-white font-semibold text-sm leading-tight truncate">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</p>
                    <p class="text-white/50 text-xs capitalize">{{ Auth::user()->role ?? 'rôle' }}</p>
                </div>
            </button>
        </div>
    </nav>

    <div class="px-4 py-6 border-t border-white/10">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-red-600/30 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
