export default (initialData) => ({
    filterOpen: false,
    timeRange: initialData.timeRange,
    customVisible: initialData.timeRange === 'Custom',

    /**
     * Change la période et affiche/cache les inputs de date custom
     */
    setRange(range) {
        this.timeRange = range;
        this.customVisible = (range === 'Custom');
    },

    /**
     * Écoute les événements Livewire pour synchroniser l'état
     */
    init() {
        this.$wire.on('update-charts', () => {
        });
    }
});
