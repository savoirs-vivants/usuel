<div class="min-h-screen bg-white flex flex-col" x-data="{
        tracker: null,

        init() {
            this.startTracker();
        },

        startTracker() {
            if (this.tracker) { this.tracker.destroy(); this.tracker = null; }

            const el = document.getElementById('q-meta-data');
            if (el && el.dataset.qid) {
                this.tracker = new window.QuestionTracker(parseInt(el.dataset.qid), parseInt(el.dataset.pos));
            }
        },

        recordChoice(lettre) {
            if (this.tracker) this.tracker.recordAnswerChange(lettre);
        },

        async validerQ() {
            const data = this.tracker ? this.tracker.collect() : {};
            if (this.tracker) { this.tracker.destroy(); this.tracker = null; }

            await this.$wire.validerAvecTracking(data);

            this.$nextTick(() => {
                this.startTracker();
            });
        },

        async jeSaisPasQ() {
            const data = this.tracker ? this.tracker.collect() : {};
            if (this.tracker) { this.tracker.destroy(); this.tracker = null; }

            await this.$wire.jeSaisPasAvecTracking(data);

            this.$nextTick(() => {
                this.startTracker();
            });
        }
    }">

    @if ($showEndModal)
        <div class="flex-1 flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <p class="text-gray-500 font-mono animate-pulse">Sauvegarde des données en cours…</p>
        </div>

        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1a2340]/40 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 relative transform scale-100 transition-transform">

                <div class="text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-6">Test terminé</p>

                    <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#1a9e7e]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-[#1a2340] mb-3 leading-snug">
                        Félicitations, vous avez<br>terminé le questionnaire !
                    </h2>
                    <p class="text-sm text-gray-500 max-w-xs mx-auto mb-8">
                        Veuillez maintenant rendre l'appareil au travailleur social pour qu'il puisse consulter les résultats.
                    </p>

                    <button wire:click="validerFinEtRediriger"
                            class="w-full inline-flex items-center justify-center gap-2 bg-sv-green hover:opacity-90 text-white text-sm font-bold px-5 py-3.5 rounded-xl transition-all shadow-md hover:scale-[1.02]">
                        L'appareil a été rendu
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

    @elseif ($currentQuestion)
        <div id="q-meta-data" data-qid="{{ $currentQuestion->id }}" data-pos="{{ $currentIndex }}" class="hidden"></div>

        <div class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 px-6 py-3">
            <div class="flex items-center gap-3 max-w-5xl mx-auto">
                <span class="font-mono text-xs font-bold text-[#1a9e7e] shrink-0 w-20 text-right">
                    {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                </span>
                <div class="flex gap-[3px] flex-1">
                    @for ($i = 0; $i < $totalQuestions; $i++)
                        <div class="h-[5px] flex-1 rounded-full transition-all duration-400
                            {{ $i < $currentIndex ? 'bg-[#1a9e7e]' : ($i === $currentIndex ? 'bg-[#1a9e7e]/50' : 'bg-gray-200') }}">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="flex-1 pt-14" style="height: calc(100vh - 3.5rem); overflow: hidden;">

            @if ($currentQuestion->image)
                <div class="h-full grid grid-cols-2">
                    <div class="h-full bg-gray-50 border-r border-gray-100 flex items-center justify-center p-6">
                        <img src="{{ asset('storage/' . $currentQuestion->image) }}"
                            alt="Illustration question {{ $currentIndex + 1 }}"
                            class="max-w-full max-h-full object-contain rounded-xl shadow-lg"
                            wire:key="img-{{ $currentQuestion->id }}">
                    </div>

                    <div class="h-full flex flex-col justify-center px-10 py-8 overflow-y-auto">
                        <p class="font-mono font-bold text-xs tracking-widest text-[#1a9e7e] uppercase mb-4">
                            Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                        </p>
                        <p class="text-xl font-semibold text-[#1a2340] leading-relaxed mb-8">
                            {{ $translatedIntitule }}
                        </p>

                        <div class="space-y-2.5 mb-7">
                            @foreach ($translatedChoix as $lettre => $choixData)
                                <button wire:key="q-{{ $currentQuestion->id }}-choix-{{ $lettre }}"
                                    wire:click="choisir('{{ $lettre }}')"
                                    @click="recordChoice('{{ $lettre }}')" data-choice="true"
                                    class="w-full text-left flex items-center gap-4 px-5 py-3 rounded-xl border-2 transition-all duration-200
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
                                     {{ $choixData['texte'] ?? $choixData }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        @if ($showError)
                            <div class="text-red-500 text-sm font-semibold mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Veuillez sélectionner une réponse ou cliquer sur "Je ne sais pas".
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <button @click="jeSaisPasQ()" data-choice="true"
                                class="flex-1 px-6 py-3 rounded-xl font-bold text-white bg-[#1a9e7e]/70 hover:bg-[#1a9e7e] transition-all duration-200 text-sm">
                                {{ $btnJeSaisPas }}
                            </button>
                            <button @click="validerQ()" @if ($selectedAnswer === '') disabled @endif
                                data-choice="true"
                                class="flex-1 px-6 py-3 rounded-xl font-bold text-white transition-all duration-200 text-sm
                                {{ $selectedAnswer === '' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#1a2340] hover:bg-[#111827] hover:scale-[1.02] shadow-md' }}">
                                {{ $btnValider }} →
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center px-6">
                    <div class="w-full max-w-3xl flex flex-col items-center">
                        <h2 class="font-mono font-bold text-sm tracking-widest text-[#1a9e7e] uppercase mb-5">
                            Question {{ $currentIndex + 1 }} / {{ $totalQuestions }}
                        </h2>
                        <p class="text-2xl md:text-3xl font-semibold text-[#1a2340] text-center leading-relaxed mb-10 max-w-2xl">
                            {{ $translatedIntitule }}
                        </p>

                        <div class="w-full space-y-3 mb-8">
                            @foreach ($translatedChoix as $lettre => $choixData)
                                <button wire:key="q-{{ $currentQuestion->id }}-choix-{{ $lettre }}"
                                    @click="recordChoice('{{ $lettre }}'); $wire.choisir('{{ $lettre }}')"
                                    data-choice="true"
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
                                        {{ $choixData['texte'] ?? $choixData }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        @if ($showError)
                            <div class="w-full text-center text-red-500 text-sm font-semibold mb-4 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Veuillez sélectionner une réponse ou cliquer sur "Je ne sais pas".
                            </div>
                        @endif

                        <div class="flex gap-3 w-full max-w-sm">
                            <button @click="jeSaisPasQ()" data-choice="true"
                                class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-[#1a9e7e]/70 hover:bg-[#1a9e7e] transition-all duration-200 text-sm">
                                {{ $btnJeSaisPas }}
                            </button>
                            <button @click="validerQ()" @if ($selectedAnswer === '') disabled @endif
                                data-choice="true"
                                class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white transition-all duration-200 text-sm
                                {{ $selectedAnswer === '' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-[#1a2340] hover:bg-[#111827] hover:scale-[1.02] shadow-md' }}">
                                {{ $btnValider }} →
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @else
        <div class="flex-1 flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-[#1a9e7e] mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
            <p class="text-gray-500 font-mono animate-pulse">Chargement de la question suivante…</p>
        </div>
    @endif
</div> <script>
    window.QuestionTracker = class QuestionTracker {
        constructor(questionId, position) {
            this.questionId = questionId;
            this.position = position;
            this._startTime = Date.now();
            this._firstInteractionAt = null;
            this._lastActivityAt = Date.now();
            this.nbClics = 0;
            this.nbChangements = 0;
            this.nbClicsHorsCible = 0;
            this.nbPauses = 0;
            this._currentAnswer = null;
            this._inPause = false;
            this._lastMousePos = null;
            this.mouseTrail = [];
            this._PAUSE_THRESHOLD_MS = 5000;
            this._MOUSE_SAMPLE_MS = 300;
            this._MOUSE_MAX_POINTS = 150;

            this._bindClickTracking();
            this._bindMouseTracking();
            this._startPauseDetection();
        }

        recordAnswerChange(lettre) {
            this._touch();
            if (this._currentAnswer !== null && this._currentAnswer !== lettre) {
                this.nbChangements++;
            }
            this._currentAnswer = lettre;
        }

        collect() {
            return {
                id_question: this.questionId,
                position: this.position,
                temps_total_ms: Date.now() - this._startTime,
                latence_ms: this._firstInteractionAt ? this._firstInteractionAt - this._startTime : 0,
                nb_clics: this.nbClics,
                nb_changements: this.nbChangements,
                nb_clics_hors_cible: this.nbClicsHorsCible,
                nb_pauses: this.nbPauses,
                suivi_souris: JSON.stringify(this.mouseTrail),
            };
        }

        destroy() {
            document.removeEventListener('click', this._onDocClick);
            document.removeEventListener('mousemove', this._onMouseMove);
            clearInterval(this._mouseSampleInterval);
            clearInterval(this._pauseInterval);
        }

        _touch() {
            const now = Date.now();
            if (this._firstInteractionAt === null) this._firstInteractionAt = now;
            this._lastActivityAt = now;
        }

        _bindClickTracking() {
            this._onDocClick = (e) => {
                this._touch();
                this.nbClics++;
                if (!e.target.closest('[data-choice]')) this.nbClicsHorsCible++;
            };
            document.addEventListener('click', this._onDocClick, {
                passive: true
            });
        }

        _bindMouseTracking() {
            this._onMouseMove = (e) => {
                this._touch();
                this._lastMousePos = {
                    x: Math.round(e.clientX),
                    y: Math.round(e.clientY)
                };
            };
            document.addEventListener('mousemove', this._onMouseMove, {
                passive: true
            });
            this._mouseSampleInterval = setInterval(() => {
                if (!this._lastMousePos) return;
                const t = Date.now() - this._startTime;
                this.mouseTrail.push([t, this._lastMousePos.x, this._lastMousePos.y]);
                if (this.mouseTrail.length > this._MOUSE_MAX_POINTS) this.mouseTrail.shift();
            }, this._MOUSE_SAMPLE_MS);
        }

        _startPauseDetection() {
            this._pauseInterval = setInterval(() => {
                const idle = Date.now() - this._lastActivityAt;
                if (idle >= this._PAUSE_THRESHOLD_MS && !this._inPause) {
                    this.nbPauses++;
                    this._inPause = true;
                } else if (idle < this._PAUSE_THRESHOLD_MS && this._inPause) {
                    this._inPause = false;
                }
            }, 1000);
        }
    };
</script>
