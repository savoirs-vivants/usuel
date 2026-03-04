<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('backoffice') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#9ca3af] hover:text-[#1a2340] transition-colors duration-200 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Retour aux utilisateurs
        </a>
        <h1 class="font-mono font-bold text-3xl text-[#1a2340] mb-2">Modifier l'utilisateur</h1>
        <p class="text-sm text-gray-500 font-sans">
            Mise à jour du profil de <span class="font-bold text-[#1a2340]">{{ $user->firstname }} {{ $user->name }}</span> ({{ $user->email }})
        </p>
    </div>

    <form wire:submit.prevent="save">

        <div class="bg-white rounded-[20px] border border-[#e5e7eb] p-8 mb-8 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">

            <div class="flex items-center gap-3 mb-8 pb-5 border-b border-[#e5e7eb]">
                <div class="w-9 h-9 rounded-[10px] bg-[#e8f5f2] text-[#16987C] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-[#1a2340] font-mono">Informations du compte</h2>
            </div>
            <div class="flex flex-col gap-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold tracking-wide uppercase text-[#1a2340] mb-2">Nom</label>
                        <input type="text" wire:model="name"
                            class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                        @error('name') <p class="text-[#ef4444] text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-wide uppercase text-[#1a2340] mb-2">Prénom</label>
                        <input type="text" wire:model="firstname"
                            class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                        @error('firstname') <p class="text-[#ef4444] text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold tracking-wide uppercase text-[#1a2340] mb-2">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                    @error('email') <p class="text-[#ef4444] text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold tracking-wide uppercase text-[#1a2340] mb-2">Structure</label>
                    @if(auth()->user()->role === 'admin')
                        <input type="text" wire:model="structure"
                            class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                        @error('structure') <p class="text-[#ef4444] text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    @else
                        <div class="w-full bg-[#f3f4f6] border-2 border-[#e5e7eb] rounded-xl px-4 py-3 text-sm text-[#6b7280] font-medium flex justify-between items-center cursor-not-allowed">
                            <span>{{ $structure ?? '—' }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wide bg-white border border-gray-200 px-2 py-1 rounded-md text-[#9ca3af]">Verrouillé</span>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-[11px] font-bold tracking-wide uppercase text-[#1a2340] mb-2">Rôle <span class="text-[#ef4444]">*</span></label>
                    @if(auth()->user()->role === 'admin')
                        <select wire:model="role"
                            class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10 cursor-pointer">
                            <option value="">-- Choisir --</option>
                            <option value="travailleur">Travailleur Social</option>
                            <option value="gestionnaire">Gestionnaire</option>
                            <option value="admin">Administrateur</option>
                        </select>
                        @error('role') <p class="text-[#ef4444] text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    @else
                        <div class="w-full bg-[#f3f4f6] border-2 border-[#e5e7eb] rounded-xl px-4 py-3 text-sm text-[#6b7280] font-medium flex justify-between items-center cursor-not-allowed">
                            <span class="capitalize">{{ $role === 'travailleur' ? 'Travailleur Social' : $role }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wide bg-white border border-gray-200 px-2 py-1 rounded-md text-[#9ca3af]">Verrouillé</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('backoffice') }}" class="inline-flex items-center gap-2 text-[#6b7280] font-semibold text-sm transition-colors duration-200 hover:text-[#1a2340]">
                Annuler
            </a>
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                class="inline-flex items-center gap-2.5 bg-[#16987C] text-white px-8 py-3.5 rounded-[14px] font-bold text-[15px] shadow-[0_4px_12px_rgba(22,152,124,0.25)] transition-all duration-200 hover:bg-[#13856c] hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(22,152,124,0.35)] disabled:opacity-60 disabled:transform-none disabled:cursor-not-allowed">
                <svg wire:loading wire:target="save" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <svg wire:loading.remove wire:target="save" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span wire:loading.remove wire:target="save">Enregistrer les modifications</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </button>
        </div>

    </form>
</div>
