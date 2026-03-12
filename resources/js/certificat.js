document.addEventListener('DOMContentLoaded', function () {
    const qrcodeEl = document.getElementById('qrcode');
    const chartEl = document.getElementById('radarChart');

    if (!qrcodeEl || !chartEl) return;

    const targetUrl = qrcodeEl.dataset.url;
    if (targetUrl) {
        new QRCode(qrcodeEl, {
            text: targetUrl,
            width: 80,
            height: 80,
            colorDark: '#1a2340',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }

    const chartDataEl = document.getElementById('chart-data');
    let rawScores = {};
    let labelsMap = {};

    if (chartDataEl) {
        try {
            rawScores = JSON.parse(chartDataEl.dataset.radarScores || '{}');
            labelsMap = JSON.parse(chartDataEl.dataset.radarLabels || '{}');
        } catch(e) {
            console.error("Erreur de parsing des données du graphique", e);
        }
    }

    const keys   = Object.keys(rawScores);
    const labels = keys.map(k => labelsMap[k] || k);
    const values = keys.map(k => parseFloat(rawScores[k]));
    const data   = values.map(v => v + 5);

    new Chart(chartEl, {
        type: 'radar',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: 'rgba(26,158,126,0.15)',
                borderColor: '#1a9e7e',
                borderWidth: 2.5,
                pointBackgroundColor: '#1a2340',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 12 },
            scales: {
                r: {
                    min: 0, max: 10,
                    ticks: { display: false },
                    grid:       { color: 'rgba(0,0,0,0.06)' },
                    angleLines: { color: 'rgba(0,0,0,0.06)' },
                    pointLabels: {
                        font: { size: 10, family: 'Inter', weight: '600' },
                        color: '#1a2340',
                        padding: 8,
                    },
                }
            },
            plugins: {
                legend:  { display: false },
                tooltip: { enabled: false },
            },
            animation: {
                onComplete: () => {
                    if (new URLSearchParams(window.location.search).get('print') === '1') {
                        window.print();
                    }
                }
            }
        }
    });
});
