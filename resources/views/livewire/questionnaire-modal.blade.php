<div>

    <button
        wire:click="ouvrir"
        class="flex items-center gap-2 bg-sv-green hover:opacity-90 transition-opacity text-white font-bold text-sm font-mono px-5 py-3 rounded-xl shadow-lg shadow-sv-green/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M5 3l14 9-14 9V3z"/>
        </svg>
        Lancer le questionnaire
    </button>

    @if($open)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center"
        x-data
        x-on:keydown.escape.window="$wire.fermer()">

        <div class="absolute inset-0 bg-[#1a2340]/60 backdrop-blur-sm" wire:click="fermer"></div>

        <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

            <div class="h-1 bg-gray-100 w-full">
                <div class="h-1 bg-[#1a9e7e] transition-all duration-500"
                     style="width: {{ ($step / 4) * 100 }}%"></div>
            </div>

            <div class="px-8 py-7">

                @if($step === 1)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-1">Étape 1 / 4</p>
                    <h2 class="text-xl font-bold text-[#1a2340] mb-1">Profil du bénéficiaire</h2>
                    <p class="text-sm text-gray-400 mb-6">
                        Ces informations permettent d'adapter les statistiques de passation.
                    </p>

                    <div class="space-y-5">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Genre</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach([
                                    ['homme',       '👨', 'Homme'],
                                    ['femme',       '👩', 'Femme'],
                                    ['autre',       '🧑', 'Autre / Non-binaire'],
                                    ['non_precise', '—',  'Préfère ne pas préciser'],
                                ] as [$val, $emoji, $label])
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition
                                              {{ $genre === $val ? 'border-[#1a9e7e] bg-emerald-50' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                                    <input type="radio" wire:model="genre" value="{{ $val }}"
                                           class="accent-[#1a9e7e] w-4 h-4 shrink-0">
                                    <span class="leading-none">{{ $emoji }}</span>
                                    <span class="text-xs font-medium text-[#1a2340]">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                            @error('genre')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="age" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                Tranche d'âge
                            </label>
                            <div class="relative">
                                <select id="age" wire:model="age"
                                        class="w-full appearance-none bg-white border rounded-lg pl-4 pr-10 py-2.5 text-sm text-[#1a2340] cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition
                                               @error('age') border-red-400 @else border-gray-200 @enderror">
                                    <option value="" disabled>Sélectionner une tranche</option>
                                    <option value="moins_18">Moins de 18 ans</option>
                                    <option value="18_25">18 – 25 ans</option>
                                    <option value="26_35">26 – 35 ans</option>
                                    <option value="36_45">36 – 45 ans</option>
                                    <option value="46_55">46 – 55 ans</option>
                                    <option value="56_65">56 – 65 ans</option>
                                    <option value="plus_65">Plus de 65 ans</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('age')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="diplome" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                Niveau de diplôme
                            </label>
                            <div class="relative">
                                <select id="diplome" wire:model="diplome"
                                        class="w-full appearance-none bg-white border rounded-lg pl-4 pr-10 py-2.5 text-sm text-[#1a2340] cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition
                                               @error('diplome') border-red-400 @else border-gray-200 @enderror">
                                    <option value="" disabled>Sélectionner un diplôme</option>
                                    <option value="aucun">Aucun diplôme</option>
                                    <option value="brevet">Brevet des collèges (DNB)</option>
                                    <option value="cap_bep">CAP / BEP</option>
                                    <option value="bac">Baccalauréat</option>
                                    <option value="bac2">Bac +2 (BTS, DUT, DEUG…)</option>
                                    <option value="bac3">Bac +3 (Licence, Bachelor…)</option>
                                    <option value="bac5">Bac +5 (Master, Ingénieur…)</option>
                                    <option value="doctorat">Doctorat (Bac +8)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('diplome')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="csp" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                Catégorie socio-professionnelle
                            </label>
                            <div class="relative">
                                <select id="csp" wire:model="csp"
                                        class="w-full appearance-none bg-white border rounded-lg pl-4 pr-10 py-2.5 text-sm text-[#1a2340] cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition
                                               @error('csp') border-red-400 @else border-gray-200 @enderror">
                                    <option value="" disabled>Sélectionner une catégorie</option>
                                    <option value="agriculteur">Agriculteur exploitant</option>
                                    <option value="artisan">Artisan, commerçant, chef d'entreprise</option>
                                    <option value="cadre">Cadre et profession intellectuelle supérieure</option>
                                    <option value="intermediaire">Profession intermédiaire</option>
                                    <option value="employe">Employé</option>
                                    <option value="ouvrier">Ouvrier</option>
                                    <option value="retraite">Retraité</option>
                                    <option value="sans_activite">Sans activité professionnelle</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('csp')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex justify-between items-center mt-7">
                        <button wire:click="fermer" class="text-sm text-gray-400 hover:text-gray-600 transition">
                            Annuler
                        </button>
                        <button wire:click="validerProfil"
                                class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                            Valider
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if($step === 2)
                <div class="text-center py-4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-6">Étape 2 / 4</p>

                    <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-[#1a9e7e]" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-[#1a2340] mb-3 leading-snug">
                        Veuillez transmettre votre appareil<br>à la personne passant le test.
                    </h2>
                    <p class="text-sm text-gray-400 max-w-xs mx-auto">
                        Le bénéficiaire va maintenant prendre en main l'appareil pour commencer le questionnaire.
                    </p>

                    <button wire:click="confirmerTransmission"
                            class="mt-8 w-full inline-flex items-center justify-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white text-sm font-semibold px-5 py-3 rounded-lg transition">
                        L'appareil a été transmis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                @endif

                @if($step === 3)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-1">Étape 3 / 4</p>
                    <h2 class="text-xl font-bold text-[#1a2340] mb-1">Votre identité</h2>
                    <p class="text-sm text-gray-400 mb-6">
                        Ces informations permettront de retrouver vos résultats par la suite.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Prénom</label>
                            <input
                                type="text"
                                wire:model="prenom"
                                placeholder="Votre prénom"
                                class="w-full border rounded-lg px-4 py-2.5 text-sm text-[#1a2340] placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition
                                       @error('prenom') border-red-400 @else border-gray-200 @enderror">
                            @error('prenom')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nom</label>
                            <input
                                type="text"
                                wire:model="nom"
                                placeholder="Votre nom de famille"
                                class="w-full border rounded-lg px-4 py-2.5 text-sm text-[#1a2340] placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] focus:border-transparent transition
                                       @error('nom') border-red-400 @else border-gray-200 @enderror">
                            @error('nom')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-7">
                        <button wire:click="$set('step', 2)"
                                class="text-sm text-gray-400 hover:text-gray-600 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour
                        </button>
                        <button wire:click="validerIdentite"
                                class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                            Continuer
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @if($step === 4)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#1a9e7e] mb-1">Étape 4 / 4</p>
                    <h2 class="text-xl font-bold text-[#1a2340] mb-4">Consentement éclairé</h2>

                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm text-gray-600 leading-relaxed max-h-48 overflow-y-auto mb-5 space-y-3">
                        <p>
                            En plus de l'analyse de vos compétences pour vous orienter vers la bonne formation, ce test soutient un
                            <strong class="text-[#1a2340]">programme de recherche participative</strong>.
                        </p>
                        <p class="font-semibold text-[#1a2340]">Ce que cela implique :</p>
                        <p>
                            Pour améliorer l'accès au numérique pour tous, nous étudions non seulement vos réponses, mais aussi votre manière d'interagir avec l'outil (vitesse, mouvements de souris, hésitations). Ces données de <em>e-tracking</em> nous permettent de comprendre scientifiquement les blocages réels pour créer des outils plus simples à l'avenir.
                        </p>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="consentement"
                            class="mt-0.5 w-4 h-4 accent-[#1a9e7e] shrink-0 cursor-pointer">
                        <span class="text-sm text-gray-600 leading-relaxed">
                            J'ai lu et j'accepte que mes données de navigation soient collectées dans le cadre de ce programme de recherche.
                        </span>
                    </label>

                    @error('consentement')
                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="flex justify-between items-center mt-7">
                        <button wire:click="$set('step', 3)"
                                class="text-sm text-gray-400 hover:text-gray-600 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Retour
                        </button>
                        <button wire:click="validerConsentement"
                                class="inline-flex items-center gap-2 bg-[#1a9e7e] hover:bg-[#158a6c] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                            Commencer le test
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>
