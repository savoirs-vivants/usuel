<div>
    <button wire:click="openModal"
        class="inline-flex items-center gap-2 bg-sv-green hover:bg-sv-green/90 text-white font-bold text-sm px-4 py-2 rounded-xl transition-all duration-150 shadow-sm shadow-sv-green/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter un utilisateur
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex"
                style="min-height: 480px;">

                <div
                    class="w-56 shrink-0 bg-sv-blue flex flex-col justify-between p-7 relative overflow-hidden rounded-l-3xl">

                    <div class="relative z-10">
                        <div
                            class="w-11 h-11 rounded-2xl bg-sv-green/20 border border-sv-green/30 flex items-center justify-center mb-5">
                            <svg class="w-5 h-5 text-sv-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-2">Administration</p>
                        <h2 class="font-mono font-bold text-xl text-white leading-snug">Nouvel<br>utilisateur</h2>
                    </div>

                    <div class="relative z-10 space-y-3">
                        <div class="flex items-start gap-2.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-sv-green mt-1.5 shrink-0"></div>
                            <p class="text-white/50 text-xs leading-relaxed">Un email d'invitation sera envoyé
                                automatiquement.</p>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-sv-green mt-1.5 shrink-0"></div>
                            <p class="text-white/50 text-xs leading-relaxed">L'utilisateur pourra définir son mot de
                                passe.</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex flex-col">

                    <div class="flex items-center justify-between px-7 pt-6 pb-4 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-400">Remplissez les informations du compte</p>
                        <button wire:click="closeModal"
                            class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-red-50 hover:text-red-500 text-gray-400 flex items-center justify-center transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="flex-1 flex flex-col px-7 py-5">
                        <div class="space-y-5 flex-1">

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-sv-blue/70 uppercase tracking-wider">Nom</label>
                                    <div class="relative">
                                        <input type="text" wire:model="name" placeholder="Dupont"
                                            class="w-full bg-gray-50 border-0 border-b-2 border-gray-200 focus:border-sv-green rounded-none px-0 py-2 outline-none text-sm font-semibold text-gray-800 transition-colors placeholder-gray-300">
                                    </div>
                                    @error('name')
                                        <p class="text-red-500 text-xs flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-sv-blue/70 uppercase tracking-wider">Prénom</label>
                                    <input type="text" wire:model="firstname" placeholder="Jean"
                                        class="w-full bg-gray-50 border-0 border-b-2 border-gray-200 focus:border-sv-green rounded-none px-0 py-2 outline-none text-sm font-semibold text-gray-800 transition-colors placeholder-gray-300">
                                    @error('firstname')
                                        <p class="text-red-500 text-xs flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-sv-blue/70 uppercase tracking-wider">
                                    Email <span class="text-red-400 normal-case tracking-normal">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute left-0 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <input type="email" wire:model="email" placeholder="jean.dupont@exemple.fr"
                                        class="w-full bg-transparent border-0 border-b-2 border-gray-200 focus:border-sv-green rounded-none pl-6 pr-0 py-2 outline-none text-sm font-semibold text-gray-800 transition-colors placeholder-gray-300">
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-xs flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-sv-blue/70 uppercase tracking-wider">
                                        Rôle <span class="text-red-400 normal-case tracking-normal">*</span>
                                    </label>
                                    <select wire:model="role"
                                        class="w-full bg-transparent border-0 border-b-2 border-gray-200 focus:border-sv-green rounded-none px-0 py-2 outline-none text-sm font-semibold text-gray-800 transition-colors">
                                        <option value="#">— Choisir —</option>
                                        <option value="travailleur">Travailleur social</option>
                                        @if (auth()->user()->role === 'admin')
                                            <option value="gestionnaire">Gestionnaire</option>
                                            <option value="admin">Administrateur</option>
                                        @endif
                                    </select>
                                    @error('role')
                                        <p class="text-red-500 text-xs flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-sv-blue/70 uppercase tracking-wider">Structure</label>
                                    @if (auth()->user()->role === 'gestionnaire')
                                        <div class="relative">
                                            <input type="text" value="{{ auth()->user()->structure }}" disabled
                                                class="w-full bg-transparent border-0 border-b-2 border-gray-100 rounded-none px-0 py-2 text-gray-400 text-sm cursor-not-allowed">
                                            <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                    @else
                                        <input type="text" wire:model="structure"
                                            placeholder="Nom de la structure"
                                            class="w-full bg-transparent border-0 border-b-2 border-gray-200 focus:border-sv-green rounded-none px-0 py-2 outline-none text-sm font-semibold text-gray-800 transition-colors placeholder-gray-300">
                                        @error('structure')
                                            <p class="text-red-500 text-xs flex items-center gap-1 mt-1">
                                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-between pt-5 mt-4 border-t border-gray-100">
                            <button type="button" wire:click="closeModal" wire:loading.attr="disabled"
                                class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-50">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="inline-flex items-center gap-2.5 bg-sv-green hover:bg-sv-green/90 text-white font-bold text-sm px-6 py-3 rounded-2xl transition-all duration-150 shadow-lg shadow-sv-green/20 disabled:opacity-70">
                                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                <svg wire:loading.remove wire:target="save" class="w-4 h-4 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span wire:loading.remove wire:target="save">Envoyer l'invitation</span>
                                <span wire:loading wire:target="save">Envoi en cours…</span>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    @endif
</div>
