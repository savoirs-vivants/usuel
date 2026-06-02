<div class="relative">
    <select wire:model.live="mode"
        class="appearance-none bg-white border border-gray-200 text-[#1a2340] text-sm font-medium rounded-lg pl-4 pr-9 py-2.5 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#1a9e7e] transition">
        <option value="fixe">Mode fixe</option>
        <option value="aleatoire">Mode aléatoire</option>
        <option value="semi_aleatoire">Mode semi-aléatoire</option>
        <option value="carre_latin">Carré latin</option>
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
</div>
