<section class="min-h-screen bg-gray-50 flex flex-col pt-12">
    <div class="min-h-screen bg-white flex flex-col" x-data="questionnaireHandler({
        audioEnabled: {{ $audio ? 'true' : 'false' }},
        speechLang: '{{ $langue }}'
    })">

        @if ($showEndModal)
            <div class="flex-1 flex flex-col items-center justify-center py-20">
                <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <p class="text-gray-500 font-mono animate-pulse">Sauvegarde des données en cours…</p>
            </div>

            <div
                class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a2340]/40 backdrop-blur-sm transition-opacity">
                <div
                    class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 relative transform scale-100 transition-transform">
                    <div class="text-center">
                        <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-6">Test terminé</p>
                        <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-[#1a9e7e]" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#1a2340] mb-3 leading-snug">
                            Félicitations, vous avez<br>terminé le questionnaire !
                        </h2>
                        <p class="text-sm text-gray-500 max-w-xs mx-auto mb-8">
                            Veuillez maintenant rendre l'appareil au travailleur social pour qu'il puisse consulter les
                            résultats.
                        </p>
                        <button wire:click="validerFinEtRediriger"
                            class="w-full inline-flex items-center justify-center gap-2 bg-sv-green hover:opacity-90 text-white text-sm font-bold px-5 py-3.5 rounded-xl transition-all shadow-md hover:scale-[1.02]">
                            L'appareil a été rendu
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @elseif ($currentQuestion)
            <div id="q-meta-data" data-qid="{{ $currentQuestion->id }}" data-pos="{{ $currentIndex }}" class="hidden">
            </div>

            <div
                class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 px-6 py-3">
                <div class="flex items-center gap-3 max-w-5xl mx-auto">
                    @if ($audio)
                        <button @click="speakQuestion()"
                            :class="isSpeaking
                                ?
                                'bg-sv-green text-white shadow-md shadow-sv-green/30 scale-105' :
                                'bg-sv-green/10 text-sv-green hover:bg-sv-green/20'"
                            class="shrink-0 flex items-center gap-2 px-4 py-1.5 rounded-full transition-all duration-200 group">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5L6 9H2v6h4l5 4V5zM19.07 4.93a10 10 0 010 14.14M15.54 8.46a5 5 0 010 7.07" />
                            </svg>

                            <span class="text-xs font-bold uppercase tracking-wide">
                                Relire la question
                            </span>
                        </button>
                    @endif
                    <span class="font-mono text-xs font-bold text-[#1a9e7e] shrink-0 w-20 text-right">
                        {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                    </span>
                    <div class="flex gap-[3px] flex-1">
                        @for ($i = 0; $i < $totalQuestions; $i++)
                            <div
                                class="h-[5px] flex-1 rounded-full transition-all duration-400
                                {{ $i < $currentIndex ? 'bg-[#1a9e7e]' : ($i === $currentIndex ? 'bg-[#1a9e7e]/50' : 'bg-gray-200') }}">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="flex-1 pt-14" style="height: calc(100vh - 3.5rem); overflow: hidden;">

                <div
                    class="h-full {{ $currentQuestion->image ? 'grid grid-cols-2' : 'flex flex-col items-center justify-center px-6' }}">

                    @if ($currentQuestion->image)
                        <div class="h-full bg-gray-50 border-r border-gray-100 flex items-center justify-center p-6">
                            <img src="{{ asset('storage/' . $currentQuestion->image) }}" alt="Illustration"
                                class="max-w-full max-h-full object-contain rounded-xl shadow-lg"
                                wire:key="img-{{ $currentQuestion->id }}">
                        </div>
                    @endif

                    <div
                        class="{{ $currentQuestion->image ? 'h-full flex flex-col justify-center px-10 py-8 overflow-y-auto' : 'w-full max-w-3xl flex flex-col items-center' }}">

                        <p class="font-mono font-bold text-xs tracking-widest text-[#1a9e7e] uppercase mb-4">
                            Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                        </p>

                        <p id="audio-intitule"
                            class="{{ $currentQuestion->image ? 'text-xl font-semibold text-[#1a2340] leading-relaxed mb-8' : 'text-2xl md:text-3xl font-semibold text-[#1a2340] text-center leading-relaxed mb-10 max-w-2xl' }}">
                            {{ $translatedIntitule }}
                        </p>

                        <div class="w-full space-y-2.5 mb-7">
                            @foreach ($translatedChoix as $lettre => $choixData)
                                <button wire:key="q-{{ $currentQuestion->id }}-choix-{{ $lettre }}"
                                    wire:click="choisir('{{ $lettre }}')"
                                    @click="recordChoice('{{ $lettre }}')" data-choice="true"
                                    class="w-full text-left flex items-center gap-4 px-5 py-3 rounded-xl border-2 transition-all duration-200
                                            {{ $selectedAnswer === $lettre ? 'border-[#1a9e7e] bg-[#1a9e7e]/10 shadow-sm' : 'border-gray-200 bg-gray-100 hover:border-gray-300 hover:bg-gray-200/50' }}">
                                    <div
                                        class="w-5 h-5 shrink-0 rounded-full border-2 flex items-center justify-center {{ $selectedAnswer === $lettre ? 'border-[#1a9e7e]' : 'border-gray-400 bg-white' }}">
                                        @if ($selectedAnswer === $lettre)
                                            <div class="w-2.5 h-2.5 bg-[#1a9e7e] rounded-full"></div>
                                        @endif
                                    </div>
                                    <span data-audio-choix class="text-gray-700 font-medium text-sm">
                                        {{ $choixData['texte'] ?? $choixData }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        @if ($showError)
                            <div class="text-red-500 text-sm font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Veuillez sélectionner une réponse.
                            </div>
                        @endif

                        <div class="flex gap-3 w-full {{ $currentQuestion->image ? '' : 'max-w-sm' }}">
                            <button @click="processAction('jeSaisPas')" data-choice="true"
                                class="flex-1 px-6 py-3 rounded-xl font-bold text-white bg-[#1a9e7e]/70 hover:bg-[#1a9e7e] transition-all duration-200 text-sm">
                                {{ $btnJeSaisPas }}
                            </button>

                            <button @click="processAction('valider')" @if ($selectedAnswer === '') disabled @endif
                                data-choice="true"
                                class="flex-1 px-6 py-3 rounded-xl font-bold text-white transition-all duration-200 text-sm
                                        {{ $selectedAnswer === '' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#1a2340] hover:bg-[#111827] hover:scale-[1.02] shadow-md' }}">
                                {{ $btnValider }} →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center py-20">
                <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                </svg>
                <p class="text-gray-500 font-mono animate-pulse">Chargement de la question suivante…</p>
            </div>
        @endif
    </div>
</section>
