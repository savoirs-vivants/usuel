import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-search]').forEach(input => {
        const targetId = input.dataset.search;
        const table = document.getElementById(targetId);
        if (!table) return;

        const rows = () => table.querySelectorAll('tbody tr');

        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();

            rows().forEach(row => {
                const text = [...row.querySelectorAll('td')]
                    .map(td => td.textContent.trim().toLowerCase())
                    .join(' ');

                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });
});
