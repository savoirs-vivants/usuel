{{-- resources/views/livewire/questionnaire-run.blade.php --}}
<div class="min-h-screen bg-white flex flex-col">

    @if ($currentQuestion)

        <div class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 px-6 py-3">
            <div class="flex items-center gap-3 max-w-5xl mx-auto">
                <span class="font-mono text-xs font-bold text-[#1a9e7e] shrink-0 w-20 text-right">
                    {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                </span>
                <div class="flex gap-[3px] flex-1">
                    @for ($i = 0; $i < $totalQuestions; $i++)
                        <div class="h-[5px] flex-1 rounded-full transition-all duration-400
                            {{ $i < $currentIndex
                                ? 'bg-[#1a9e7e]'
                                : ($i === $currentIndex
                                    ? 'bg-[#1a9e7e]/50'
                                    : 'bg-gray-200') }}">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="flex-1 pt-16 pb-10">

            @if ($currentQuestion->image)

                <div class="max-w-4xl mx-auto px-6 py-8 flex flex-col items-center">

                    <h2 class="font-mono font-bold text-sm tracking-widest text-[#1a9e7e] uppercase mb-5 self-start">
                        Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                    </h2>

                    <p class="text-xl md:text-2xl font-semibold text-[#1a2340] text-center leading-relaxed mb-8 max-w-3xl">
                        {{ $currentQuestion->intitule }}
                    </p>

                    <div class="w-full rounded-2xl overflow-hidden border border-gray-200 shadow-xl bg-gray-50 mb-8">
                        <img
                            src="{{ asset('storage/questions/' . $currentQuestion->image) }}"
                            alt="Illustration question {{ $currentIndex + 1 }}"
                            class="w-full object-contain max-h-[520px]"
                            wire:key="img-{{ $currentQuestion->id }}">
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                        @foreach ($currentQuestion->choixSansE as $lettre => $choixData)
                        <button
                            wire:key="q-{{ $currentQuestion->id }}-choix-{{ $lettre }}"
                            wire:click="choisir('{{ $lettre }}')"
                            class="w-full text-left flex items-center gap-4 px-5 py-3.5 rounded-xl border-2 transition-all duration-200
                                {{ $selectedAnswer === $lettre
                                    ? 'border-[#1a9e7e] bg-[#1a9e7e]/10 shadow-sm'
                                    : 'border-gray-200 bg-gray-100 hover:border-gray-300 hover:bg-gray-200/50' }}">
                            <div class="w-5 h-5 shrink-0 rounded-full border-2 flex items-center justify-center
                                {{ $selectedAnswer === $lettre ? 'border-[#1a9e7e]' : 'border-gray-400 bg-white' }}">
                                @if ($selectedAnswer === $lettre)
                                <div class="w-2.5 h-2.5 bg-[#1a9e7e] rounded-full"></div>
                                @endif
                            </div>
                            <span class="text-gray-700 font-medium text-sm">
                                {{ $lettre }}) {{ $choixData['texte'] ?? $choixData }}
                            </span>
                        </button>
                        @endforeach
                    </div>

                    @if ($showError)
                    <div class="w-full text-center text-red-500 text-sm font-semibold mb-4 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Veuillez sélectionner une réponse ou cliquer sur "Je ne sais pas".
                    </div>
                    @endif

                    <div class="flex gap-3 w-full max-w-sm">
                        <button wire:click="jeSaisPas"
                            class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-[#1a9e7e]/70 hover:bg-[#1a9e7e] transition-all duration-200 text-sm">
                            Je ne sais pas
                        </button>
                        <button wire:click="valider" @if ($selectedAnswer === '') disabled @endif
                            class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white transition-all duration-200 text-sm
                                {{ $selectedAnswer === ''
                                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                    : 'bg-[#1a2340] hover:bg-[#111827] hover:scale-[1.02] shadow-md' }}">
                            Valider →
                        </button>
                    </div>

                </div>
            @else

                <div class="max-w-3xl mx-auto px-6 py-12 flex flex-col items-center">

                    <h2 class="font-mono font-bold text-sm tracking-widest text-[#1a9e7e] uppercase mb-5">
                        Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                    </h2>

                    <p class="text-2xl md:text-3xl font-semibold text-[#1a2340] text-center leading-relaxed mb-12 max-w-2xl">
                        {{ $currentQuestion->intitule }}
                    </p>

                    <div class="w-full space-y-3 mb-10">
                        @foreach ($currentQuestion->choixSansE as $lettre => $choixData)
                        <button
                            wire:key="q-{{ $currentQuestion->id }}-choix-{{ $lettre }}"
                            wire:click="choisir('{{ $lettre }}')"
                            class="w-full text-left flex items-center gap-4 px-6 py-4 rounded-xl border-2 transition-all duration-200
                                {{ $selectedAnswer === $lettre
                                    ? 'border-[#1a9e7e] bg-[#1a9e7e]/10 shadow-sm'
                                    : 'border-gray-200 bg-gray-100 hover:border-gray-300 hover:bg-gray-200/50' }}">
                            <div class="w-5 h-5 shrink-0 rounded-full border-2 flex items-center justify-center
                                {{ $selectedAnswer === $lettre ? 'border-[#1a9e7e]' : 'border-gray-400 bg-white' }}">
                                @if ($selectedAnswer === $lettre)
                                <div class="w-2.5 h-2.5 bg-[#1a9e7e] rounded-full"></div>
                                @endif
                            </div>
                            <span class="text-gray-700 font-medium">
                                {{ $lettre }}) {{ $choixData['texte'] ?? $choixData }}
                            </span>
                        </button>
                        @endforeach
                    </div>

                    @if ($showError)
                    <div class="w-full text-center text-red-500 text-sm font-semibold mb-4 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Veuillez sélectionner une réponse ou cliquer sur "Je ne sais pas".
                    </div>
                    @endif

                    <div class="flex gap-3 w-full max-w-sm">
                        <button wire:click="jeSaisPas"
                            class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-[#1a9e7e]/70 hover:bg-[#1a9e7e] transition-all duration-200 text-sm">
                            Je ne sais pas
                        </button>
                        <button wire:click="valider" @if ($selectedAnswer === '') disabled @endif
                            class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white transition-all duration-200 text-sm
                                {{ $selectedAnswer === ''
                                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                    : 'bg-[#1a2340] hover:bg-[#111827] hover:scale-[1.02] shadow-md' }}">
                            Valider →
                        </button>
                    </div>

                </div>

            @endif

        </div>
    @else

        <div class="flex-1 flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            <p class="text-gray-500 font-mono animate-pulse">Calcul de vos résultats en cours…</p>
        </div>

    @endif

</div>
