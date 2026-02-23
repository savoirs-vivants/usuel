<div class="w-full max-w-4xl mx-auto px-6 pb-20">

    @if($this->currentQuestion)

        <div class="flex gap-1 mb-10 w-full mx-auto">
            @for ($i = 0; $i < $totalQuestions; $i++)
                <div class="h-1.5 flex-1 rounded-full transition-colors duration-300 {{ $i <= $currentIndex ? 'bg-[#1a9e7e]' : 'bg-gray-200' }}"></div>
            @endfor
        </div>

        <div class="text-center mb-10">
            <h2 class="font-mono font-bold text-sm tracking-widest text-[#1a2340] uppercase mb-6">
                Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
            </h2>
            <p class="text-xl md:text-2xl text-gray-800 font-medium max-w-2xl mx-auto leading-relaxed">
                {{ $this->currentQuestion->intitule }}
            </p>
        </div>

        <div class="space-y-4 max-w-2xl mx-auto mb-10">
            @foreach($this->currentQuestion->choixSansE as $lettre => $choixData)
                <button
                    wire:click="choisir('{{ $lettre }}')"
                    class="w-full text-left flex items-center gap-4 p-4 rounded-xl border-2 transition-all duration-200
                    {{ $selectedAnswer === $lettre
                        ? 'border-[#1a9e7e] bg-[#1a9e7e]/10 shadow-sm'
                        : 'border-gray-200 bg-gray-100 hover:border-gray-300 hover:bg-gray-200/50' }}">

                    <div class="w-5 h-5 shrink-0 rounded-full border-2 flex items-center justify-center
                        {{ $selectedAnswer === $lettre ? 'border-[#1a9e7e]' : 'border-gray-400 bg-white' }}">
                        @if($selectedAnswer === $lettre)
                            <div class="w-2.5 h-2.5 bg-[#1a9e7e] rounded-full"></div>
                        @endif
                    </div>

                    <span class="text-gray-700 font-medium">
                        {{ $lettre }}) {{ $choixData['texte'] ?? $choixData }}
                    </span>
                </button>
            @endforeach
        </div>

        @if($showError)
            <div class="text-center text-red-500 text-sm font-bold mb-6 animate-pulse">
                Veuillez sélectionner une réponse ou cliquer sur "Je ne sais pas".
            </div>
        @endif

        <div class="flex justify-center gap-4">
            <button
                wire:click="jeSaisPas"
                class="px-8 py-3 rounded-xl font-bold text-white bg-[#1a9e7e] hover:bg-[#158a6c] transition-colors shadow-md w-48">
                Je ne sais pas
            </button>

            <button
                wire:click="valider"
                class="px-8 py-3 rounded-xl font-bold text-white bg-[#1a9e7e] hover:bg-[#158a6c] transition-colors shadow-md w-48">
                Valider
            </button>
        </div>

    @else
        <div class="flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            <p class="text-gray-500 font-mono animate-pulse">Calcul de vos résultats en cours...</p>
        </div>
    @endif
</div>
