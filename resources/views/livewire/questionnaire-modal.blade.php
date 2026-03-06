<div>

    <button
        wire:click="ouvrir"
        class="group inline-flex items-center gap-3 bg-sv-green hover:bg-sv-blue active:scale-95
               text-white font-grotesk font-bold text-base
               px-7 py-4 rounded-2xl shadow-lg shadow-sv-green/30
               transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sv-green/40">
        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white/20 group-hover:bg-white/30 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 3l14 9-14 9V3z"/>
            </svg>
        </span>
        Lancer le questionnaire
    </button>

    @if($open)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data
        x-on:keydown.escape.window="$wire.fermer()">

        <div
            class="absolute inset-0 bg-sv-blue/70 backdrop-blur-md"
            wire:click="fermer"></div>

        <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-xl mx-auto overflow-hidden
                    flex flex-col"
             style="max-height: 92svh;">

            <div class="h-1.5 bg-gray-100 w-full shrink-0">
                <div class="h-full bg-sv-green rounded-full transition-all duration-700 ease-out"
                     style="width: {{ ($step / 4) * 100 }}%"></div>
            </div>

            <div class="bg-sv-blue px-8 pt-6 pb-5 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-7 h-7 rounded-full font-mono text-xs font-bold transition-all duration-300
                                        {{ $step >= $i ? 'bg-sv-green text-white' : 'bg-white/10 text-white/40' }}">
                                @if($step > $i)
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            @if($i < 4)
                            <div class="w-6 h-px {{ $step > $i ? 'bg-sv-green' : 'bg-white/15' }} transition-all duration-300"></div>
                            @endif
                        </div>
                        @endfor
                    </div>
                    <button wire:click="fermer"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-white/10 hover:bg-white/20
                                   text-white/60 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-white/30"
                            aria-label="Fermer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 px-8 py-7">

                @if($step === 1)
                <div>
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-sv-green mb-1">Étape 1 sur 4</p>
                    <h2 class="font-grotesk text-2xl font-bold text-sv-blue mb-1 leading-tight">Profil du bénéficiaire</h2>
                    <p class="text-sm text-gray-400 font-grotesk mb-7 leading-relaxed">
                        Ces informations permettent d'adapter les statistiques de passation.
                    </p>

                    <div class="space-y-6">

                        <div>
                            <label class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Genre
                            </label>
                            <div class="grid grid-cols-2 gap-2.5">
                                @foreach([
                                    ['homme',       '👨', 'Homme'],
                                    ['femme',       '👩', 'Femme'],
                                    ['autre',       '🧑', 'Autre / Non-binaire'],
                                    ['non_precise', '—',  'Préfère ne pas préciser'],
                                ] as [$val, $emoji, $label])
                                <label class="relative flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer
                                              transition-all duration-150 select-none
                                              {{ $genre === $val
                                                 ? 'border-sv-green bg-sv-green/5 shadow-sm shadow-sv-green/20'
                                                 : 'border-gray-100 bg-gray-50 hover:border-gray-300 hover:bg-white active:scale-95' }}">
                                    <input type="radio" wire:model.live="genre" value="{{ $val }}"
                                           class="sr-only">
                                    <span class="text-xl leading-none">{{ $emoji }}</span>
                                    <span class="font-grotesk text-sm font-semibold
                                                 {{ $genre === $val ? 'text-sv-blue' : 'text-gray-600' }}">
                                        {{ $label }}
                                    </span>
                                    @if($genre === $val)
                                    <span class="absolute top-2.5 right-2.5 flex items-center justify-center
                                                 w-4 h-4 rounded-full bg-sv-green">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                             stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    @endif
                                </label>
                                @endforeach
                            </div>
                            @error('genre')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="age" class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Tranche d'âge
                            </label>
                            <div class="relative">
                                <select id="age" wire:model="age"
                                        class="w-full appearance-none font-grotesk bg-gray-50 border-2 rounded-2xl
                                               pl-5 pr-12 py-3.5 text-sm font-medium text-sv-blue
                                               cursor-pointer focus:outline-none focus:ring-4 focus:ring-sv-green/20
                                               focus:border-sv-green transition-all duration-150
                                               @error('age') border-red-300 bg-red-50 @else border-gray-100 hover:border-gray-300 @enderror">
                                    <option value="" disabled>Sélectionner votre tranche d'âge</option>
                                    <option value="moins_18">Moins de 18 ans</option>
                                    <option value="18_25">18 – 25 ans</option>
                                    <option value="26_35">26 – 35 ans</option>
                                    <option value="36_45">36 – 45 ans</option>
                                    <option value="46_55">46 – 55 ans</option>
                                    <option value="56_65">56 – 65 ans</option>
                                    <option value="plus_65">Plus de 65 ans</option>
                                </select>
                            </div>
                            @error('age')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="diplome" class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Niveau de diplôme
                            </label>
                            <div class="relative">
                                <select id="diplome" wire:model="diplome"
                                        class="w-full appearance-none font-grotesk bg-gray-50 border-2 rounded-2xl
                                               pl-5 pr-12 py-3.5 text-sm font-medium text-sv-blue
                                               cursor-pointer focus:outline-none focus:ring-4 focus:ring-sv-green/20
                                               focus:border-sv-green transition-all duration-150
                                               @error('diplome') border-red-300 bg-red-50 @else border-gray-100 hover:border-gray-300 @enderror">
                                    <option value="" disabled>Sélectionner votre diplôme</option>
                                    <option value="aucun">Aucun diplôme</option>
                                    <option value="brevet">Brevet des collèges (DNB)</option>
                                    <option value="cap_bep">CAP / BEP</option>
                                    <option value="bac">Baccalauréat</option>
                                    <option value="bac2">Bac +2 (BTS, DUT, DEUG…)</option>
                                    <option value="bac3">Bac +3 (Licence, Bachelor…)</option>
                                    <option value="bac5">Bac +5 (Master, Ingénieur…)</option>
                                    <option value="doctorat">Doctorat (Bac +8)</option>
                                </select>
                            </div>
                            @error('diplome')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="csp" class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Catégorie socio-professionnelle
                            </label>
                            <div class="relative">
                                <select id="csp" wire:model="csp"
                                        class="w-full appearance-none font-grotesk bg-gray-50 border-2 rounded-2xl
                                               pl-5 pr-12 py-3.5 text-sm font-medium text-sv-blue
                                               cursor-pointer focus:outline-none focus:ring-4 focus:ring-sv-green/20
                                               focus:border-sv-green transition-all duration-150
                                               @error('csp') border-red-300 bg-red-50 @else border-gray-100 hover:border-gray-300 @enderror">
                                    <option value="" disabled>Sélectionner une catégorie</option>
                                    <option value="agriculteur">Agriculteur exploitant</option>
                                    <option value="artisan">Artisan, commerçant, chef d'entreprise</option>
                                    <option value="cadre">Cadre et profession intellectuelle supérieure</option>
                                    <option value="intermediaire">Profession intermédiaire</option>
                                    <option value="employe">Employé</option>
                                    <option value="ouvrier">Ouvrier</option>
                                    <option value="retraite">Retraité</option>
                                    <option value="sans_activite">Sans activité professionnelle</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            @error('csp')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex flex-col gap-3 mt-8">
                        <button wire:click="validerProfil"
                                class="w-full inline-flex items-center justify-center gap-3
                                       bg-sv-green hover:bg-sv-blue active:scale-95
                                       text-white font-grotesk font-bold text-base
                                       px-6 py-4 rounded-2xl shadow-md shadow-sv-green/25
                                       transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sv-green/30">
                            Valider et continuer
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button wire:click="fermer"
                                class="w-full font-grotesk text-sm font-medium text-gray-400 hover:text-gray-600
                                       py-2.5 rounded-xl transition focus:outline-none">
                            Annuler
                        </button>
                    </div>
                </div>
                @endif

                @if($step === 2)
                <div class="flex flex-col items-center text-center py-4">
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-sv-green mb-8">Étape 2 sur 4</p>

                    <div class="relative mb-8">
                        <div class="w-28 h-28 rounded-full bg-sv-green/10 flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-sv-green/15 flex items-center justify-center">
                                <svg class="w-10 h-10 text-sv-green" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute -right-2 top-1/2 -translate-y-1/2">
                            <svg class="w-6 h-6 text-sv-green/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>

                    <h2 class="font-grotesk text-2xl font-bold text-sv-blue mb-3 leading-snug max-w-xs">
                        Transmettez l'appareil à la&nbsp;personne
                    </h2>
                    <p class="font-grotesk text-sm text-gray-400 max-w-xs leading-relaxed">
                        Le bénéficiaire va maintenant prendre en main l'appareil pour répondre au questionnaire.
                    </p>

                    <div class="w-full mt-10 space-y-3">
                        <button wire:click="confirmerTransmission"
                                class="w-full inline-flex items-center justify-center gap-3
                                       bg-sv-green hover:bg-sv-blue active:scale-95
                                       text-white font-grotesk font-bold text-base
                                       px-6 py-4 rounded-2xl shadow-md shadow-sv-green/25
                                       transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sv-green/30">
                            L'appareil a été transmis
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button wire:click="$set('step', 1)"
                                class="w-full font-grotesk text-sm font-medium text-gray-400 hover:text-gray-600
                                       py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour
                        </button>
                    </div>
                </div>
                @endif

                @if($step === 3)
                <div>
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-sv-green mb-1">Étape 3 sur 4</p>
                    <h2 class="font-grotesk text-2xl font-bold text-sv-blue mb-1 leading-tight">Votre identité</h2>
                    <p class="font-grotesk text-sm text-gray-400 mb-7 leading-relaxed">
                        Ces informations permettront de retrouver vos résultats par la suite.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <label class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Prénom
                            </label>
                            <input
                                type="text"
                                wire:model="prenom"
                                placeholder="Votre prénom"
                                class="w-full font-grotesk bg-gray-50 border-2 rounded-2xl
                                       px-5 py-3.5 text-base font-medium text-sv-blue placeholder-gray-300
                                       focus:outline-none focus:ring-4 focus:ring-sv-green/20 focus:border-sv-green
                                       transition-all duration-150
                                       @error('prenom') border-red-300 bg-red-50 @else border-gray-100 hover:border-gray-300 @enderror">
                            @error('prenom')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-grotesk text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                Nom de famille
                            </label>
                            <input
                                type="text"
                                wire:model="nom"
                                placeholder="Votre nom de famille"
                                class="w-full font-grotesk bg-gray-50 border-2 rounded-2xl
                                       px-5 py-3.5 text-base font-medium text-sv-blue placeholder-gray-300
                                       focus:outline-none focus:ring-4 focus:ring-sv-green/20 focus:border-sv-green
                                       transition-all duration-150
                                       @error('nom') border-red-300 bg-red-50 @else border-gray-100 hover:border-gray-300 @enderror">
                            @error('nom')
                            <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 mt-8">
                        <button wire:click="validerIdentite"
                                class="w-full inline-flex items-center justify-center gap-3
                                       bg-sv-green hover:bg-sv-blue active:scale-95
                                       text-white font-grotesk font-bold text-base
                                       px-6 py-4 rounded-2xl shadow-md shadow-sv-green/25
                                       transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sv-green/30">
                            Continuer
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button wire:click="$set('step', 2)"
                                class="w-full font-grotesk text-sm font-medium text-gray-400 hover:text-gray-600
                                       py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour
                        </button>
                    </div>
                </div>
                @endif

                @if($step === 4)
                <div>
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-sv-green mb-1">Étape 4 sur 4</p>
                    <h2 class="font-grotesk text-2xl font-bold text-sv-blue mb-5 leading-tight">Consentement éclairé</h2>

                    <div class="bg-sv-blue/5 border-2 border-sv-blue/10 rounded-2xl p-5 mb-5
                                max-h-48 overflow-y-auto space-y-3 scroll-smooth">
                        <p class="font-grotesk text-sm text-gray-600 leading-relaxed">
                            En plus de l'analyse de vos compétences pour vous orienter vers la bonne formation, ce test soutient un
                            <strong class="text-sv-blue font-semibold">programme de recherche participative</strong>.
                        </p>
                        <p class="font-grotesk text-sm font-bold text-sv-blue">Ce que cela implique :</p>
                        <p class="font-grotesk text-sm text-gray-600 leading-relaxed">
                            Pour améliorer l'accès au numérique pour tous, nous étudions non seulement vos réponses,
                            mais aussi votre manière d'interagir avec l'outil (vitesse, mouvements de souris, hésitations).
                            Ces données de <em>e-tracking</em> nous permettent de comprendre scientifiquement les blocages
                            réels et de créer des outils plus simples à l'avenir.
                        </p>
                    </div>

                    <label class="flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150
                                  {{ $consentement ? 'border-sv-green bg-sv-green/5' : 'border-gray-100 bg-gray-50 hover:border-gray-300' }}">
                        <div class="relative shrink-0 mt-0.5">
                            <input
                                type="checkbox"
                                wire:model.live="consentement"
                                class="sr-only">
                            <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all duration-150
                                        {{ $consentement ? 'bg-sv-green border-sv-green' : 'border-gray-300 bg-white' }}">
                                @if($consentement)
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                @endif
                            </div>
                        </div>
                        <span class="font-grotesk text-sm font-medium text-gray-600 leading-relaxed">
                            J'ai lu et j'accepte que mes données de navigation soient collectées dans le cadre de ce programme de recherche.
                        </span>
                    </label>

                    @error('consentement')
                    <p class="mt-2 text-xs text-red-500 font-grotesk flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="flex flex-col gap-3 mt-8">
                        <button wire:click="validerConsentement"
                                class="w-full inline-flex items-center justify-center gap-3
                                       bg-sv-green hover:bg-sv-blue active:scale-95
                                       text-white font-grotesk font-bold text-base
                                       px-6 py-4 rounded-2xl shadow-md shadow-sv-green/25
                                       transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sv-green/30">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 3l14 9-14 9V3z"/>
                            </svg>
                            Commencer le test
                        </button>
                        <button wire:click="$set('step', 3)"
                                class="w-full font-grotesk text-sm font-medium text-gray-400 hover:text-gray-600
                                       py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>
