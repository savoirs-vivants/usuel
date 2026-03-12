<div class="flex h-screen font-grotesk overflow-hidden bg-slate-100">

    <aside
        class="w-96 shrink-0 flex flex-col h-screen relative bg-sv-blue from-slate-900 via-slate-800 to-slate-900">
        <div class="relative px-5 pt-6 pb-5 shrink-0 border-b border-white/10">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-sv-green"></span>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/50">Back-office</p>
                    </div>
                    <h2 class="font-mono font-bold text-white text-xl leading-tight tracking-tight">Questions</h2>
                </div>
                <a href="{{ route('questionnaire.index') }}"
                    class="mt-0.5 w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 text-white/40 hover:bg-white/20 hover:text-white transition-all"
                    title="Retour">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <button wire:click="nouvelleQuestion"
                class="w-full flex items-center justify-center gap-2.5 text-white text-sm font-bold px-4 py-3 rounded-xl transition-all hover:-translate-y-px hover:opacity-90 bg-gradient-to-br from-sv-green to-emerald-700 shadow-lg shadow-sv-green/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter une question
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1.5 custom-scrollbar">

            @if ($form->isNew)
                <div class="rounded-xl p-3.5 bg-sv-green/20 border border-sv-green/40">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-sv-green animate-pulse"></span>
                        <p class="text-[10px] font-bold text-sv-green uppercase tracking-widest">Nouvelle</p>
                    </div>
                    <p class="text-sm font-semibold text-white truncate">Question en création…</p>
                </div>
            @endif

            @forelse ($questions as $q)
                <div class="relative group">
                    <button wire:click="selectQuestion({{ $q['id'] }})"
                        class="w-full text-left rounded-xl p-3.5 transition-all
                        {{ !$q['active'] ? 'opacity-40 grayscale' : '' }}
                        {{ $form->selectedId === $q['id'] && !$form->isNew
                            ? 'bg-sv-green/20 border border-sv-green/40'
                            : 'bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20' }}">
                        <div class="flex items-start gap-3 pr-8">
                            <span
                                class="font-mono text-[11px] font-bold shrink-0 mt-0.5 {{ $form->selectedId === $q['id'] && !$form->isNew ? 'text-sv-green' : 'text-white/30' }}">
                                #{{ $q['id'] }}
                            </span>
                            <div class="min-w-0">
                                <p
                                    class="text-[10px] font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5 {{ $form->selectedId === $q['id'] && !$form->isNew ? 'text-sv-green' : 'text-white/40' }}">
                                    {{ $form->categories[$q['categorie']] ?? $q['categorie'] }}
                                    @if (!$q['active'])
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">DÉSACTIVÉE</span>
                                    @endif
                                </p>
                                <p
                                    class="text-[13px] font-medium leading-snug line-clamp-2 {{ $form->selectedId === $q['id'] && !$form->isNew ? 'text-white' : 'text-white/80' }}">
                                    {{ $q['intitule'] ?: '(sans intitulé)' }}
                                </p>
                            </div>
                        </div>
                    </button>

                    <button wire:click="toggleActive({{ $q['id'] }})"
                        class="absolute top-3 right-3 w-7 h-7 flex items-center justify-center rounded-lg transition-all {{ $q['active'] ? 'text-gray-400 hover:text-white hover:bg-white/10' : 'text-red-400 hover:text-red-300 hover:bg-red-400/10' }}"
                        title="{{ $q['active'] ? 'Désactiver la question' : 'Réactiver la question' }}">
                        @if ($q['active'])
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        @endif
                    </button>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-xs text-white/30">Aucune question.</p>
                </div>
            @endforelse
        </div>

        <div class="px-5 py-4 shrink-0 flex items-center gap-2 border-t border-white/10">
            <span class="text-[10px] font-bold uppercase tracking-widest text-white/20">{{ count($questions) }}
                question{{ count($questions) > 1 ? 's' : '' }}</span>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-slate-100">

        @if ($form->selectedId === null && !$form->isNew)

            <div class="flex flex-col items-center justify-center h-full gap-4">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-slate-200/50">
                    <svg class="w-9 h-9 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.25"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="font-semibold text-sm text-slate-500">Sélectionnez une question</p>
                    <p class="text-xs mt-1 text-slate-400">ou créez-en une nouvelle</p>
                </div>
            </div>
        @else
            <form wire:submit.prevent="sauvegarder" class="max-w-2xl mx-auto px-8 py-8 space-y-5">

                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            @if ($form->isNew)
                                <span
                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-sv-green">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sv-green animate-pulse"></span>
                                    Nouvelle question
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                    <span class="font-mono">Édition — #{{ $form->selectedId }}</span>
                                </span>
                            @endif
                        </div>
                        <h1 class="font-mono font-bold text-2xl text-sv-blue tracking-tight">Éditeur de questions</h1>
                    </div>

                    @if ($saved)
                        <div
                            class="flex items-center gap-2.5 text-sm font-semibold px-4 py-2.5 rounded-xl bg-sv-green/10 border border-sv-green/30 text-sv-green">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $savedMessage }}
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm">
                    <div class="px-6 py-3.5 flex items-center gap-3 border-b border-slate-100 bg-slate-50/50">
                        <div class="w-1.5 h-5 rounded-full bg-sv-green"></div>
                        <p class="text-[11px] font-bold text-sv-blue uppercase tracking-widest opacity-60">Question</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <textarea wire:model="form.intitule" rows="3" placeholder="Saisissez l'intitulé de la question…"
                                class="w-full rounded-xl px-4 py-3.5 text-sm font-medium text-sv-blue placeholder-slate-300 outline-none resize-none leading-relaxed transition-all bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-sv-green focus:ring-4 focus:ring-sv-green/10"></textarea>
                            @error('form.intitule')
                                <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-sv-blue uppercase tracking-widest opacity-50 mb-2">Catégorie</label>
                            <div class="relative">
                                <select wire:model="form.categorie"
                                    class="w-full appearance-none rounded-xl px-4 py-3 pr-10 text-sm font-semibold text-sv-blue outline-none transition-all cursor-pointer bg-slate-50 border-2 border-slate-100 focus:bg-white focus:border-sv-green focus:ring-4 focus:ring-sv-green/10">
                                    @foreach ($form->categories as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-slate-400"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('form.categorie')
                                <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm">
                    <div class="px-6 py-3.5 flex items-center gap-3 border-b border-slate-100 bg-slate-50/50">
                        <div class="w-1.5 h-5 rounded-full bg-slate-300"></div>
                        <p class="text-[11px] font-bold text-sv-blue uppercase tracking-widest opacity-60">Image <span
                                class="normal-case tracking-normal font-medium opacity-60">(optionnelle)</span></p>
                    </div>
                    <div class="p-6">
                        <input type="file" id="image-input" wire:model="form.newImage" accept="image/*"
                            class="hidden">

                        @if ($form->newImage)
                            <div class="relative rounded-xl overflow-hidden bg-slate-50 border-2 border-sv-green/40">
                                <img src="{{ $form->newImage->temporaryUrl() }}" alt="Aperçu"
                                    class="w-full max-h-64 object-contain">
                                <button type="button" wire:click="supprimerImage"
                                    class="absolute top-3 right-3 w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center transition-all hover:bg-red-600 shadow-md shadow-red-500/40">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @elseif ($form->existingImage && !$form->removeImage)
                            <div class="relative rounded-xl overflow-hidden bg-slate-50 border-2 border-slate-200">
                                <img src="{{ Storage::url($form->existingImage) }}" alt="Image actuelle"
                                    class="w-full max-h-64 object-contain">
                                <button type="button" wire:click="supprimerImage"
                                    class="absolute top-3 right-3 w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center transition-all hover:bg-red-600 shadow-md shadow-red-500/40"
                                    title="Supprimer l'image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <button type="button" onclick="document.getElementById('image-input').click()"
                                class="w-full h-44 flex flex-col items-center justify-center gap-4 rounded-xl transition-all group cursor-pointer bg-slate-50 border-2 border-dashed border-slate-300 hover:border-sv-green hover:bg-sv-green/5">
                                <div
                                    class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all bg-slate-200/50 group-hover:bg-sv-green/10">
                                    <svg class="w-6 h-6 transition-colors text-slate-400 group-hover:text-sv-green"
                                        fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-slate-500 group-hover:text-sv-green">Cliquer
                                        pour ajouter une image</p>
                                    <p class="text-xs mt-0.5 text-slate-400">PNG, JPG, GIF — max 2 Mo</p>
                                </div>
                            </button>
                        @endif

                        @error('form.newImage')
                            <p class="text-red-500 text-xs mt-3 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm">
                    <div
                        class="px-6 py-3.5 flex items-center justify-between border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-5 rounded-full bg-sv-blue opacity-30"></div>
                            <p class="text-[11px] font-bold text-sv-blue uppercase tracking-widest opacity-60">Réponses
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <label class="text-[11px] font-semibold text-slate-500">Nombre :</label>
                            <div class="relative">
                                <select wire:model.live="form.nbReponses"
                                    class="appearance-none text-sm font-bold text-sv-blue rounded-lg pl-3 pr-7 py-1.5 outline-none cursor-pointer transition-all bg-white border-2 border-slate-200 focus:border-sv-blue">
                                    @for ($i = 2; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 pointer-events-none text-slate-400"
                                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-3">
                        <div
                            class="flex items-center gap-2 mb-4 text-xs font-medium rounded-lg px-3.5 py-2.5 bg-sv-green/10 text-slate-600 border border-sv-green/20">
                            <svg class="w-3.5 h-3.5 text-sv-green shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cliquez sur la case verte pour marquer la bonne réponse. Ajustez le poids de chaque option.
                        </div>

                        @foreach ($form->choix as $i => $reponse)
                            <div
                                class="rounded-xl p-3.5 transition-all border-2
                         {{ ($reponse['poids'] ?? '0') === '1' ? 'bg-sv-green/5 border-sv-green/30' : 'bg-slate-50 border-slate-100' }}">
                                <div class="flex items-center gap-3">

                                    <span
                                        class="w-7 h-7 rounded-lg flex items-center justify-center font-mono font-bold text-xs shrink-0 transition-all
                                {{ ($reponse['poids'] ?? '0') === '1' ? 'bg-sv-green/20 text-sv-green' : 'bg-slate-200 text-slate-500' }}">
                                        {{ chr(65 + $i) }}
                                    </span>

                                    <button type="button" wire:click="toggleCorrect({{ $i }})"
                                        title="Marquer comme bonne réponse"
                                        class="w-6 h-6 rounded-md border-2 flex items-center justify-center shrink-0 transition-all
                                {{ ($reponse['poids'] ?? '0') === '1' ? 'bg-sv-green border-sv-green shadow-md shadow-sv-green/40' : 'bg-white border-slate-300' }}">
                                        @if (($reponse['poids'] ?? '0') === '1')
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                                stroke-width="3" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </button>

                                    <input type="text" wire:model="form.choix.{{ $i }}.texte"
                                        placeholder="Réponse {{ chr(65 + $i) }}…"
                                        class="flex-1 rounded-lg px-3.5 py-2 text-sm font-medium text-sv-blue placeholder-slate-300 outline-none transition-all bg-white border-2 border-slate-100 focus:border-sv-green focus:ring-4 focus:ring-sv-green/10">

                                    <div class="relative shrink-0">
                                        <select wire:model="form.choix.{{ $i }}.poids"
                                            class="appearance-none text-xs font-bold border-2 rounded-lg pl-2.5 pr-6 py-2 outline-none cursor-pointer transition-all
                                    {{ ($reponse['poids'] ?? '0') === '1'
                                        ? 'bg-sv-green/10 border-sv-green/40 text-sv-green'
                                        : (($reponse['poids'] ?? '0') === '0.5'
                                            ? 'bg-sv-green/5 border-sv-green/30 text-sv-green'
                                            : (($reponse['poids'] ?? '0') === '-0.5'
                                                ? 'bg-amber-50 border-amber-200 text-amber-600'
                                                : (($reponse['poids'] ?? '0') === '-1'
                                                    ? 'bg-red-50 border-red-200 text-red-600'
                                                    : 'bg-slate-100 border-slate-200 text-slate-500'))) }}">
                                            @foreach ($poidsOptions as $val => $label)
                                                <option value="{{ $val }}">
                                                    {{ $val > 0 ? '+' : '' }}{{ $val }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="absolute right-1.5 top-1/2 -translate-y-1/2 w-3 h-3 pointer-events-none opacity-60"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('form.choix.' . $i . '.texte')
                                    <p class="text-red-500 text-xs mt-2 ml-16 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pb-10 pt-2">
                    <button type="button" wire:click="previsualiser"
                        class="inline-flex items-center gap-2.5 text-sm font-bold px-5 py-3 rounded-xl transition-all bg-white border border-slate-200 text-slate-600 shadow-sm hover:border-slate-400 hover:text-sv-blue">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Prévisualiser
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="sauvegarder"
                        class="inline-flex items-center gap-2.5 text-white font-bold text-sm px-8 py-3.5 rounded-xl transition-all disabled:opacity-60 bg-gradient-to-br from-sv-green to-emerald-700 shadow-lg shadow-sv-green/40 hover:-translate-y-px hover:shadow-xl hover:shadow-sv-green/50">
                        <svg wire:loading wire:target="sauvegarder" class="animate-spin w-4 h-4" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                        </svg>
                        <svg wire:loading.remove wire:target="sauvegarder" class="w-4 h-4" fill="none"
                            stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span wire:loading.remove wire:target="sauvegarder">Valider les modifications</span>
                        <span wire:loading wire:target="sauvegarder">Enregistrement…</span>
                    </button>
                </div>

            </form>
        @endif
    </main>

    @if ($showPreview)
        <div class="fixed inset-0 z-50 min-h-screen bg-white flex flex-col">
            <div class="shrink-0 px-6 py-4 flex items-center gap-4 border-b border-slate-200">
                <button wire:click="fermerPrevisualisation"
                    class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl transition-all bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-sv-blue">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à l'éditeur
                </button>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Prévisualisation</span>
            </div>
            <div class="flex-1 overflow-hidden">
                @php
                    $hasImage = $form->newImage || ($form->existingImage && !$form->removeImage);
                @endphp
                @if ($hasImage)
                    <div class="h-full grid grid-cols-2">

                        <div class="h-full bg-slate-50 border-r border-slate-200 flex items-center justify-center p-6">
                            @if ($form->newImage)
                                <img src="{{ $form->newImage->temporaryUrl() }}" alt="Illustration"
                                    class="max-w-full max-h-full object-contain rounded-xl shadow-lg">
                            @else
                                <img src="{{ Storage::url($form->existingImage) }}" alt="Illustration"
                                    class="max-w-full max-h-full object-contain rounded-xl shadow-lg">
                            @endif
                        </div>
                        <div class="h-full flex flex-col justify-center px-10 py-8 overflow-y-auto">
                            <p class="font-mono font-bold text-xs tracking-widest text-sv-green uppercase mb-4">
                                Prévisualisation
                            </p>
                            <p class="text-xl font-semibold text-sv-blue leading-relaxed mb-8">
                                {{ $form->intitule ?: '(aucun intitulé)' }}
                            </p>
                            <div class="space-y-2.5">
                                @foreach ($form->choix as $i => $reponse)
                                    @if (trim($reponse['texte']) !== '')
                                        <div
                                            class="w-full text-left flex items-center gap-4 px-5 py-3 rounded-xl border-2 border-slate-200 bg-slate-100">
                                            <div
                                                class="w-5 h-5 shrink-0 rounded-full border-2 border-slate-400 bg-white">
                                            </div>
                                            <span class="text-slate-700 font-medium text-sm">
                                                {{ chr(65 + $i) }}) {{ $reponse['texte'] }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center px-6">
                        <div class="w-full max-w-3xl flex flex-col items-center">
                            <h2 class="font-mono font-bold text-sm tracking-widest text-sv-green uppercase mb-5">
                                Prévisualisation
                            </h2>
                            <p
                                class="text-2xl md:text-3xl font-semibold text-sv-blue text-center leading-relaxed mb-10 max-w-2xl">
                                {{ $form->intitule ?: '(aucun intitulé)' }}
                            </p>
                            <div class="w-full space-y-3">
                                @foreach ($form->choix as $i => $reponse)
                                    @if (trim($reponse['texte']) !== '')
                                        <div
                                            class="w-full text-left flex items-center gap-4 px-6 py-4 rounded-xl border-2 border-slate-200 bg-slate-100">
                                            <div
                                                class="w-5 h-5 shrink-0 rounded-full border-2 border-slate-400 bg-white">
                                            </div>
                                            <span class="text-slate-700 font-medium">
                                                {{ chr(65 + $i) }}) {{ $reponse['texte'] }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
