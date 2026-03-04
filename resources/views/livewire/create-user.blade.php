<div>
    <button wire:click="openModal"
        class="flex items-center gap-2 bg-sv-green hover:opacity-90 transition-opacity text-white font-bold text-sm font-mono px-5 py-3 rounded-xl shadow-lg shadow-sv-green/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter un utilisateur
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-sv-blue/80 backdrop-blur-sm transition-opacity">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-mono font-bold text-xl text-sv-blue">Nouvel utilisateur</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6 space-y-4">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-sv-blue mb-1">Nom</label>
                            <input type="text" wire:model="name"
                                class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg p-2.5 outline-none focus:border-sv-green text-sm">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-sv-blue mb-1">Prénom</label>
                            <input type="text" wire:model="firstname"
                                class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg p-2.5 outline-none focus:border-sv-green text-sm">
                            @error('firstname')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-sv-blue mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email"
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg p-2.5 outline-none focus:border-sv-green text-sm">
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-sv-blue mb-1">Rôle <span class="text-red-500">*</span></label>
                            <select wire:model="role"
                                class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg p-2.5 outline-none focus:border-sv-green text-sm">
                                <option value="travailleur">Travailleur Social</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="gestionnaire">Gestionnaire</option>
                                    <option value="admin">Administrateur</option>
                                @endif
                            </select>
                            @error('role')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-sv-blue mb-1">Structure</label>
                            @if(auth()->user()->role === 'gestionnaire')
                                <input type="text"
                                    value="{{ auth()->user()->structure }}"
                                    disabled
                                    class="w-full bg-gray-200 border-2 border-gray-200 rounded-lg p-2.5 outline-none text-gray-500 text-sm cursor-not-allowed">
                            @else
                                <input type="text" wire:model="structure"
                                    class="w-full bg-gray-50 border-2 border-gray-200 rounded-lg p-2.5 outline-none focus:border-sv-green text-sm">
                                @error('structure')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeModal"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors disabled:opacity-50">
                            Annuler
                        </button>

                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="px-5 py-2.5 rounded-xl font-bold text-sm bg-sv-green text-white hover:opacity-90 transition-opacity flex items-center gap-2 disabled:opacity-70">

                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>

                            <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    @endif
</div>
