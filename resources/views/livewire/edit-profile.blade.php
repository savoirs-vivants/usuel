<div class="max-w-2xl">

    <div class="mb-8">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Compte</p>
        <h1 class="font-mono font-bold text-3xl text-sv-blue">Mon profil</h1>
        <p class="text-sm text-gray-400 mt-1">Modifiez vos informations personnelles.</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Infos personnelles --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Informations personnelles</p>
            </div>
            <div class="p-6 space-y-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Nom</label>
                        <input type="text" wire:model="name"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Prénom</label>
                        <input type="text" wire:model="firstname"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                        @error('firstname')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Structure</label>
                    <input type="text" wire:model="structure"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                    @error('structure')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Rôle</label>
                    <div class="bg-gray-50 border-2 border-gray-100 rounded-xl px-3.5 py-2.5 text-sm text-gray-400 capitalize select-none">
                        {{ Auth::user()->role ?? '—' }}
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Le rôle ne peut pas être modifié ici.</p>
                </div>

            </div>
        </div>

        {{-- Mot de passe --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Mot de passe</p>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-xs text-gray-400">Laissez vide pour conserver votre mot de passe actuel.</p>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Nouveau mot de passe</label>
                    <div class="relative">
                        <input id="input-password" type="password" wire:model="password"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 pr-10 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue"
                            placeholder="8 caractères minimum">
                        <button type="button" onclick="togglePassword('input-password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-green transition-colors">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Confirmer le mot de passe</label>
                    <div class="relative">
                        <input id="input-password-confirm" type="password" wire:model="password_confirm"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 pr-10 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue"
                            placeholder="Répétez le mot de passe">
                        <button type="button" onclick="togglePassword('input-password-confirm', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-green transition-colors">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ url()->previous() }}"
                class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors">
                Annuler
            </a>
            <button type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm bg-sv-green text-white hover:bg-sv-green/90 transition-colors disabled:opacity-70 shadow-sm">
                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span wire:loading.remove wire:target="save">Enregistrer</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </button>
        </div>

    </form>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            button.classList.add('text-sv-green');
            button.classList.remove('text-gray-400');
        } else {
            input.type = 'password';
            button.classList.remove('text-sv-green');
            button.classList.add('text-gray-400');
        }
    }
</script>
</div>
