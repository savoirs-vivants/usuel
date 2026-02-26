<div x-data="{ open: false }" x-on:open-export-modal.window="open = true">

    <button
        @click="open = true"
        class="bg-[#1a9e7e] hover:bg-[#158a6c] text-white px-6 py-2 rounded-lg font-semibold transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Exporter
    </button>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="open = false">

        <div
            class="absolute inset-0 bg-[#1a2340]/50 backdrop-blur-sm"
            @click="open = false">
        </div>

        <div
            class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">

            <div class="px-6 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[#1a2340]">Exporter les statistiques</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Données filtrées selon vos critères actuels</p>
                </div>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5">
                <div class="grid grid-cols-2 gap-3">
                    <button
                        wire:click="exportCsv"
                        @click="open = false"
                        class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-gray-100 hover:border-[#1a9e7e] hover:bg-emerald-50 transition group cursor-pointer">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-emerald-100 flex items-center justify-center transition">
                            <svg class="w-6 h-6 text-gray-500 group-hover:text-[#1a9e7e] transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-sm text-[#1a2340]">CSV</p>
                            <p class="text-xs text-gray-400 mt-0.5">Compatible Excel,<br>Google Sheets…</p>
                        </div>
                    </button>

                    <button
                        wire:click="exportExcel"
                        @click="open = false"
                        class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-gray-100 hover:border-[#1a9e7e] hover:bg-emerald-50 transition group cursor-pointer">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-emerald-100 flex items-center justify-center transition">
                            <svg class="w-6 h-6 text-gray-500 group-hover:text-[#1a9e7e] transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0c.621 0 1.125.504 1.125 1.125v1.5"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-bold text-sm text-[#1a2340]">Excel</p>
                            <p class="text-xs text-gray-400 mt-0.5">Fichier .xlsx avec<br>plusieurs onglets</p>
                        </div>
                    </button>

                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <p class="text-xs text-gray-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    Les filtres et la période sélectionnés sont appliqués à l'export.
                </p>
            </div>

        </div>
    </div>

</div>
