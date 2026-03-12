export default (ids = []) => ({
    selected: [],
    allIds: ids,
    confirmBulkDelete: false,

    get allSelected() {
        return this.allIds.length > 0 && this.selected.length === this.allIds.length;
    },

    get someSelected() {
        return this.selected.length > 0 && !this.allSelected;
    },

    toggleAll() {
        if (this.allSelected) {
            this.selected = [];
        } else {
            this.selected = [...this.allIds];
        }
    },

    toggle(id) {
        if (this.selected.includes(id)) {
            this.selected = this.selected.filter(i => i !== id);
        } else {
            this.selected.push(id);
        }
    },

    /**
     * Soumet le formulaire de suppression groupée dynamiquement.
     * @param {string} routeUrl - L'URL de la route vers le contrôleur.
     */
    submitBulkDelete(routeUrl) {
        if (this.selected.length === 0) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = routeUrl;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                     || '';
        form.appendChild(csrf);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        this.selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
});
