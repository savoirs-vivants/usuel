{{-- resources/views/components/toast-notification.blade.php --}}
<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timer: null,

        init() {
            @if (session('toast_message'))
                this.message = '{{ session('toast_message') }}';
                this.type    = '{{ session('toast_type', 'success') }}';
                this.show    = true;
                this.timer   = setTimeout(() => this.show = false, 3500);
            @endif
            window.addEventListener('toast', (e) => {
                this.message = e.detail.message ?? e.detail[0]?.message ?? '';
                this.type    = e.detail.type    ?? e.detail[0]?.type    ?? 'success';
                this.show    = true;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this.show = false, 3500);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-cloak
    class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-semibold min-w-64 max-w-sm"
    :class="{
        'bg-white border-sv-green text-sv-green': type === 'success',
        'bg-white border-red-400 text-red-500':   type === 'error',
        'bg-white border-sv-blue text-sv-blue':   type === 'info',
    }">

    <div class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center"
        :class="{
            'bg-sv-green/10': type === 'success',
            'bg-red-50':      type === 'error',
            'bg-sv-blue/10':  type === 'info',
        }">
        <svg x-show="type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <svg x-show="type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <svg x-show="type === 'info'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
        </svg>
    </div>

    <span x-text="message" class="flex-1"></span>

    <button @click="show = false" class="shrink-0 opacity-40 hover:opacity-100 transition-opacity">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
