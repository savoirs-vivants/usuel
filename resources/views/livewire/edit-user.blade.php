<div class="max-w-2xl">

    <div class="mb-8">
        <a href="{{ route('backoffice') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-sv-blue transition-colors mb-4">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux utilisateurs
        </a>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Back-office</p>
        <h1 class="font-mono font-bold text-3xl text-sv-blue">Modifier l'utilisateur</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $user->firstname }} {{ $user->name }} — {{ $user->email }}</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">

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
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Prénom</label>
                        <input type="text" wire:model="firstname"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                        @error('firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Structure</label>
                    <input type="text" wire:model="structure"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                    @error('structure') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-sv-blue uppercase tracking-wider mb-1.5">Rôle <span class="text-red-500">*</span></label>
                    <select wire:model="role"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-3.5 py-2.5 text-sm outline-none focus:border-sv-green transition-colors text-sv-blue">
                        <option value="">-- Choisir --</option>
                        <option value="travailleur">Travailleur Social</option>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('backoffice') }}"
                class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors">
                Annuler
            </a>
            <button type="submit"
                wire:loading.attr="disabled" wire:target="save"
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
</div>
