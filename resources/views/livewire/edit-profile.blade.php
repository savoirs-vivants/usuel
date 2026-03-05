<div>
    <form wire:submit.prevent="save">

        <div class="bg-white rounded-[20px] border border-[#e5e7eb] p-8 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#e5e7eb]">
                <div class="w-9 h-9 rounded-[10px] bg-[#e8f5f2] text-[#16987C] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-[#1a2340] font-mono">Informations personnelles</h2>
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-xs font-bold text-[#1a2340] mb-2">Nom</label>
                    <input type="text" wire:model="name" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                    @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#1a2340] mb-2">Prénom</label>
                    <input type="text" wire:model="firstname" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                    @error('firstname') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1a2340] mb-2">Adresse e-mail</label>
                <input type="email" wire:model="email" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                @error('email') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1a2340] mb-2">Structure</label>
                <input type="text" wire:model="structure" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10">
                @error('structure') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 mb-2">Rôle attribué</label>
                <div class="w-full bg-[#f3f4f6] border-2 border-[#e5e7eb] rounded-xl px-4 py-3 text-sm text-[#6b7280] font-medium flex justify-between items-center">
                    <span class="capitalize">{{ Auth::user()->role }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wide bg-white border border-gray-200 px-2 py-1 rounded-md text-[#9ca3af]">Verrouillé</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-[#e5e7eb] p-8 mb-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#e5e7eb]">
                <div class="w-9 h-9 rounded-[10px] bg-[#e8f5f2] text-[#16987C] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-base font-bold text-[#1a2340] font-mono">Sécurité</h2>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-6 flex gap-3 items-start">
                <svg class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-900/70 font-medium">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe actuel.</p>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1a2340] mb-2">Nouveau mot de passe</label>
                <div class="relative">
                    <input id="pwd1" type="password" wire:model="password" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 pr-12 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10" placeholder="8 caractères minimum">
                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#16987C] transition-colors" onclick="togglePwd('pwd1')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#1a2340] mb-2">Confirmer le mot de passe</label>
                <div class="relative">
                    <input id="pwd2" type="password" wire:model="password_confirm" class="w-full bg-[#f9fafb] border-2 border-[#f3f4f6] rounded-xl px-4 py-3 pr-12 text-sm text-[#1a2340] font-medium transition-all duration-200 outline-none placeholder:text-[#9ca3af] placeholder:font-normal focus:bg-white focus:border-[#16987C] focus:ring-[4px] focus:ring-[#16987C]/10" placeholder="Répétez le mot de passe">
                    <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#16987C] transition-colors" onclick="togglePwd('pwd2')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password_confirm') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center justify-between mt-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-[#6b7280] font-semibold text-sm transition-colors duration-200 hover:text-[#1a2340]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Retour
            </a>

            <button type="submit" wire:loading.attr="disabled" wire:target="save" class="inline-flex items-center gap-2.5 bg-[#16987C] text-white px-8 py-3.5 rounded-[14px] font-bold text-[15px] shadow-[0_4px_12px_rgba(22,152,124,0.25)] transition-all duration-200 hover:bg-[#13856c] hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(22,152,124,0.35)] disabled:opacity-60 disabled:transform-none disabled:cursor-not-allowed">
                <svg wire:loading wire:target="save" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span wire:loading.remove wire:target="save">Mettre à jour le profil</span>
                <span wire:loading wire:target="save">Enregistrement...</span>
            </button>
        </div>

    </form>

    <script>
        function togglePwd(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</div>
