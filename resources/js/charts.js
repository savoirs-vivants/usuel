document.addEventListener('DOMContentLoaded', function () {

    /* ─────────────────────────────────────────
     * UTILITAIRES PARTAGÉS
     * ───────────────────────────────────────── */

    const COLORS = {
        blue:   'rgb(54, 162, 235)',
        green:  'rgb(75, 192, 192)',
        grey:   'rgb(201, 203, 207)',
        orange: 'rgb(255, 159, 64)',
        purple: 'rgb(153, 102, 255)',
        red:    'rgb(255, 99, 132)',
        yellow: 'rgb(255, 205, 86)',
        teal:   'rgb(20, 184, 166)',
        indigo: 'rgb(99, 102, 241)',
        pink:   'rgb(236, 72, 153)',
        sky:    'rgb(14, 165, 233)',
        cyan:   'rgb(6, 182, 212)',
        svGreen:'rgb(26, 158, 126)',
    };

    const ALPHA = Object.fromEntries(
        Object.entries(COLORS).map(([k, v]) => [k, v.replace('rgb', 'rgba').replace(')', ', 0.6)')])
    );

    const CYAN_A   = 'rgba(6,182,212,0.75)';
    const ORANGE_A = 'rgba(249,115,22,0.75)';

    const tooltipBase = {
        backgroundColor: '#1a2340',
        titleColor:      '#fff',
        bodyColor:       '#9ca3af',
        borderColor:     '#374151',
        borderWidth:     1,
        padding:         10,
        boxPadding:      6,
    };

    const axisTicks = {
        color: '#9ca3af',
        font:  { size: 11 },
        padding: 8,
    };

    /** Lit le nœud #chart-data et retourne l'objet JSON de l'attribut demandé. */
    function readData(attr) {
        const el = document.getElementById('chart-data');
        if (!el) return null;
        try { return JSON.parse(el.dataset[attr] ?? 'null'); }
        catch (e) { console.error('chart-data JSON parse error :', attr, e); return null; }
    }

    /** Détruit une instance Chart.js si elle existe. */
    function destroyChart(instance) {
        if (instance) { instance.destroy(); }
        return null;
    }


    /* ═══════════════════════════════════════════
     * 1. DASHBOARD
     * ═══════════════════════════════════════════ */

    const ctxBar   = document.getElementById('chartMois');
    const ctxDonut = document.getElementById('chartScores');

    if (ctxBar || ctxDonut) {
        const labelsBar  = readData('labelsBarChart')    ?? [];
        const dataBar    = readData('dataBarChart')      ?? [];
        const repartition = readData('repartitionScores') ?? [0, 0, 0];

        if (ctxBar) {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labelsBar,
                    datasets: [{
                        data: dataBar,
                        backgroundColor: '#1a9e7e22',
                        borderColor:     '#1a9e7e',
                        borderWidth:     2,
                        borderRadius:    8,
                        borderSkipped:   false,
                    }],
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend:  { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} passation(s)` } },
                    },
                    scales: {
                        x: {
                            grid:  { display: false },
                            ticks: { font: { size: 11 }, color: '#9ca3af' },
                        },
                        y: {
                            beginAtZero: true,
                            ticks:  { stepSize: 1, font: { size: 11 }, color: '#9ca3af' },
                            grid:   { color: '#f3f4f6' },
                        },
                    },
                },
            });
        }

        if (ctxDonut) {
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Score faible (< 0)', 'Score moyen (0–15)', 'Score élevé (> 15)'],
                    datasets: [{
                        data:            repartition,
                        backgroundColor: ['#ef444422', '#f59e0b22', '#1a9e7e22'],
                        borderColor:     ['#ef4444',   '#f59e0b',   '#1a9e7e'],
                        borderWidth:     2,
                    }],
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: false,
                    cutout:              '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 11 }, padding: 12,
                                boxWidth: 12, color: '#374151',
                            },
                        },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed} passation(s)` } },
                    },
                },
            });
        }
    }


    /* ═══════════════════════════════════════════
     * 2. STATISTIQUES — PUBLIC
     * ═══════════════════════════════════════════ */

    const ctxAge   = document.getElementById('ageChart');
    const ctxGenre = document.getElementById('genreChart');
    const ctxCsp   = document.getElementById('cspChart');
    const ctxDim   = document.getElementById('dimChart');

    if (ctxAge || ctxGenre || ctxCsp || ctxDim) {
        let ageChart = null, genreChart = null, cspChart = null, dimChart = null;

        const pieColors     = [COLORS.sky, COLORS.pink, COLORS.yellow, COLORS.purple];
        const doughnutColors = [
            COLORS.teal, COLORS.indigo, COLORS.orange,
            COLORS.red,  COLORS.green,  COLORS.blue,
        ];

        function initPublicCharts(data) {
            if (!data) return;

            ageChart   = destroyChart(ageChart);
            genreChart = destroyChart(genreChart);
            cspChart   = destroyChart(cspChart);
            dimChart   = destroyChart(dimChart);

            const hasAge   = data.age_labels?.length   > 0;
            const hasGenre = data.genre_labels?.length  > 0;
            const hasCsp   = data.csp_labels?.length    > 0;
            const hasDim   = data.dim_labels?.length    > 0;

            /* — Âge (bar) — */
            if (ctxAge) {
                ageChart = new Chart(ctxAge, {
                    type: 'bar',
                    data: {
                        labels: hasAge ? data.age_labels : ['Aucune donnée'],
                        datasets: [{
                            label: 'Score',
                            data:  hasAge ? data.age_scores : [0],
                            backgroundColor: (ctx) =>
                                !hasAge ? ALPHA.grey :
                                ctx.raw >= 0 ? 'rgba(26,158,126,0.85)' : 'rgba(239,68,68,0.85)',
                            borderRadius: (ctx) => {
                                const v = ctx.raw;
                                return v >= 0
                                    ? { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 }
                                    : { topLeft: 0, topRight: 0, bottomLeft: 6, bottomRight: 6 };
                            },
                            borderSkipped:      false,
                            barPercentage:      0.55,
                            categoryPercentage: 0.8,
                        }],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend:  { display: false },
                            tooltip: {
                                ...tooltipBase,
                                callbacks: { label: ctx => ` Score: ${Number(ctx.raw).toFixed(1)}` },
                            },
                        },
                        scales: {
                            x: {
                                grid:   { display: false, drawTicks: false },
                                border: { display: false },
                                ticks:  { ...axisTicks, autoSkip: false, maxRotation: 0 },
                            },
                            y: {
                                grid: {
                                    color:     ctx => ctx.tick.value === 0 ? '#d1d5db' : '#f3f4f6',
                                    lineWidth: ctx => ctx.tick.value === 0 ? 2 : 1,
                                    drawTicks: false,
                                },
                                border: { display: false },
                                ticks:  axisTicks,
                            },
                        },
                        interaction: { mode: 'index', intersect: false },
                    },
                });
            }

            /* — Genre (pie) — */
            if (ctxGenre) {
                genreChart = new Chart(ctxGenre, {
                    type: 'pie',
                    data: {
                        labels: hasGenre ? data.genre_labels : ['Aucune donnée'],
                        datasets: [{
                            data:            hasGenre ? data.genre_counts : [1],
                            backgroundColor: hasGenre
                                ? pieColors.slice(0, data.genre_labels.length)
                                : [ALPHA.grey],
                            borderWidth: 3, borderColor: '#fff', hoverOffset: 15,
                        }],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20, usePointStyle: true,
                                    pointStyle: 'circle', font: { size: 12, weight: '500' },
                                    color: '#374151',
                                },
                            },
                            tooltip: { backgroundColor: '#1a2340' },
                        },
                    },
                });
            }

            /* — CSP (doughnut) — */
            if (ctxCsp) {
                cspChart = new Chart(ctxCsp, {
                    type: 'doughnut',
                    data: {
                        labels: hasCsp ? data.csp_labels : ['Aucune donnée'],
                        datasets: [{
                            data:            hasCsp ? data.csp_counts : [1],
                            backgroundColor: hasCsp
                                ? doughnutColors.slice(0, data.csp_labels.length)
                                : [ALPHA.grey],
                            borderWidth: 3, borderColor: '#fff',
                            hoverOffset: 15, cutout: '60%',
                        }],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 12, usePointStyle: true,
                                    pointStyle: 'circle', font: { size: 12, weight: '500' },
                                    color: '#374151',
                                },
                            },
                            tooltip: {
                                backgroundColor: '#1a2340',
                                callbacks: {
                                    label(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct   = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                        return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                                    },
                                },
                            },
                        },
                    },
                });
            }

            /* — Dimensions (radar) — */
            if (ctxDim) {
                dimChart = new Chart(ctxDim, {
                    type: 'radar',
                    data: {
                        labels: hasDim ? data.dim_labels : ['Chargement…'],
                        datasets: [{
                            label:                'Score Normalisé',
                            data:                 hasDim ? data.dim_scores : [],
                            fill:                 true,
                            backgroundColor:      ALPHA.teal,
                            borderColor:          COLORS.teal,
                            borderWidth:          2.5,
                            pointBackgroundColor: COLORS.teal,
                            pointBorderColor:     '#fff',
                            pointBorderWidth:     2,
                            pointRadius:          5,
                            pointHoverRadius:     8,
                        }],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            r: {
                                min: 0, max: 10, ticks: {
                                    stepSize:       2,
                                    backdropColor:  'transparent',
                                    color:          '#9ca3af',
                                    font:           { size: 11 },
                                    callback:       v => (v - 5).toFixed(0),
                                },
                                grid:        { color: 'rgba(0,0,0,0.06)' },
                                angleLines:  { color: 'rgba(0,0,0,0.06)' },
                                pointLabels: { font: { size: 12, weight: '600' }, color: '#374151' },
                            },
                        },
                        plugins: {
                            legend:  { display: false },
                            tooltip: {
                                backgroundColor: '#1a2340',
                                callbacks: { label: ctx => ` Score : ${(ctx.raw - 5).toFixed(1)} / 5` },
                            },
                        },
                    },
                });
            }

            const el = document.getElementById('totalPassations');
            if (el) el.textContent = data.total_passations;
        }

        initPublicCharts(readData('chartData'));

        window.addEventListener('update-charts', function (e) {
            const payload = e.detail;
            const data = Array.isArray(payload) ? payload[0] : (payload.data ?? payload);
            initPublicCharts(data);
        });
    }


    /* ═══════════════════════════════════════════
     * 3. STATISTIQUES — COMPORTEMENTALE
     * ═══════════════════════════════════════════ */

    const ctxLatence     = document.getElementById('latenceChart');
    const ctxChangements = document.getElementById('changementsChart');
    const ctxOrdre       = document.getElementById('ordreChart');

    if (ctxLatence || ctxChangements || ctxOrdre) {
        let latenceChart = null, changementsChart = null, ordreChart = null;

        function initComportementaleCharts(data) {
            if (!data) return;

            latenceChart     = destroyChart(latenceChart);
            changementsChart = destroyChart(changementsChart);
            ordreChart       = destroyChart(ordreChart);

            const hasL = data.top5_latence?.length     > 0;
            const hasC = data.top5_changements?.length  > 0;
            const hasO = data.ordre_positions?.length   > 0;

            /* — Latence (bar horizontal) — */
            if (ctxLatence) {
                latenceChart = new Chart(ctxLatence, {
                    type: 'bar',
                    data: {
                        labels: hasL ? data.top5_latence.map(d => d.label) : ['Aucune donnée'],
                        datasets: [{
                            data:            hasL ? data.top5_latence.map(d => d.value) : [0],
                            backgroundColor: CYAN_A,
                            borderColor:     COLORS.cyan,
                            borderWidth:     1.5,
                            borderRadius:    { topLeft: 0, topRight: 6, bottomLeft: 0, bottomRight: 6 },
                            borderSkipped:   'left',
                            barPercentage:   0.6,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend:  { display: false },
                            tooltip: {
                                ...tooltipBase,
                                callbacks: { label: c => ` ${c.raw} ms` },
                            },
                        },
                        scales: {
                            x: {
                                grid:   { display: false }, border: { display: false },
                                ticks:  { ...axisTicks, callback: v => v + ' ms' },
                            },
                            y: {
                                grid:   { display: false }, border: { display: false },
                                ticks:  axisTicks,
                            },
                        },
                    },
                });
            }

            /* — Changements (bar vertical) — */
            if (ctxChangements) {
                changementsChart = new Chart(ctxChangements, {
                    type: 'bar',
                    data: {
                        labels: hasC ? data.top5_changements.map(d => d.label) : ['Aucune donnée'],
                        datasets: [{
                            data:            hasC ? data.top5_changements.map(d => d.value) : [0],
                            backgroundColor: ORANGE_A,
                            borderColor:     COLORS.orange,
                            borderWidth:     1.5,
                            borderRadius:    { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                            borderSkipped:   false,
                            barPercentage:   0.55,
                        }],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend:  { display: false },
                            tooltip: {
                                ...tooltipBase,
                                callbacks: { label: c => ` ${c.raw} changement(s) moy.` },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false }, border: { display: false },
                                ticks: axisTicks,
                            },
                            y: {
                                grid:        { color: '#f3f4f6' },
                                border:      { display: false },
                                ticks:       axisTicks,
                                beginAtZero: true,
                            },
                        },
                    },
                });
            }

            /* — Ordre (line) — */
            if (ctxOrdre) {
                ordreChart = new Chart(ctxOrdre, {
                    type: 'line',
                    data: {
                        labels: hasO
                            ? data.ordre_positions.map((_, i) => 'Pos. ' + (i + 1))
                            : ['Aucune donnée'],
                        datasets: [
                            {
                                label:       'Temps total moy. (ms)',
                                data:        hasO ? data.ordre_temps : [0],
                                borderColor: COLORS.svGreen,
                                fill: false, tension: 0.35,
                                pointRadius: 3, pointHoverRadius: 6, borderWidth: 2,
                            },
                            {
                                label:       'Latence moy. (ms)',
                                data:        hasO ? data.ordre_latence : [0],
                                borderColor: COLORS.cyan,
                                fill: false, tension: 0.35,
                                pointRadius: 3, pointHoverRadius: 6,
                                borderWidth: 2, borderDash: [4, 3],
                            },
                            {
                                label:       'Changements moy.',
                                data:        hasO ? data.ordre_changements : [0],
                                borderColor: COLORS.orange,
                                fill: false, tension: 0.35,
                                pointRadius: 3, pointHoverRadius: 6,
                                borderWidth: 2, borderDash: [2, 3],
                                yAxisID: 'y2',
                            },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                position: 'top', align: 'end',
                                labels: {
                                    usePointStyle: true, pointStyle: 'circle',
                                    font: { size: 11 }, color: '#374151', padding: 16,
                                },
                            },
                            tooltip: tooltipBase,
                        },
                        scales: {
                            x: {
                                grid: { display: false }, border: { display: false },
                                ticks: { ...axisTicks, maxTicksLimit: 15 },
                            },
                            y: {
                                grid:        { color: '#f3f4f6' },
                                border:      { display: false },
                                ticks:       axisTicks,
                                beginAtZero: true,
                                title: {
                                    display: true, text: 'ms',
                                    color: '#9ca3af', font: { size: 10 },
                                },
                            },
                            y2: {
                                position:    'right',
                                beginAtZero: true,
                                grid:        { display: false },
                                border:      { display: false },
                                ticks:       { ...axisTicks, font: { size: 10 } },
                                title: {
                                    display: true, text: 'changements',
                                    color: '#9ca3af', font: { size: 10 },
                                },
                            },
                        },
                    },
                });
            }

            const el = document.getElementById('totalPassations');
            if (el) el.textContent = data.total_passations;
        }

        initComportementaleCharts(readData('trackingData'));

        window.addEventListener('update-charts', function (e) {
            const payload = e.detail;
            const data = Array.isArray(payload) ? payload[0] : (payload.data ?? payload);
            initComportementaleCharts(data);
        });
    }

    /* ═══════════════════════════════════════════
     * 4. RÉSULTAT INDIVIDUEL — RADAR
     * ═══════════════════════════════════════════ */

    const ctxRadar = document.getElementById('radarChart');

    if (ctxRadar) {
        // Lecture des données depuis les attributs data-* (passées par Blade)
        const rawScores = readData('radarScores') || {};
        const labelsMap = readData('radarLabels') || {};
        const catColors = readData('radarColors') || {};

        const keys       = Object.keys(rawScores);
        const labels     = keys.map(k => labelsMap[k] ?? k);
        const values     = keys.map(k => parseFloat(rawScores[k]));
        const colors     = keys.map(k => catColors[k] ?? '#6b7280');
        // Normalisation (ex: si le score va de -5 à 5, on ajoute +5 pour l'affichage radar)
        const normalized = values.map(v => Math.round((v + 5) * 10) / 10);

        new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Score',
                    data: normalized,
                    backgroundColor: 'rgba(26, 158, 126, 0.08)',
                    borderColor: '#1a9e7e',
                    borderWidth: 2.5,
                    pointBackgroundColor: colors,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2.5,
                    pointRadius: 7,
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: colors,
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1100,
                    easing: 'easeOutQuart',
                    delay: ctx => ctx.dataIndex * 80,
                },
                scales: {
                    r: {
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 2,
                            callback: val => (val - 5) >= 0 ? `${val - 5}` : `${val - 5}`,
                            font: { size: 10 },
                            color: '#9ca3af',
                            backdropColor: 'transparent',
                        },
                        grid: { color: 'rgba(26,35,64,0.06)' },
                        angleLines: { color: 'rgba(26,35,64,0.08)' },
                        pointLabels: {
                            font: { size: 12, weight: '700' },
                            color: '#374151',
                        },
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a2340',
                        borderColor: 'rgba(26,158,126,0.3)',
                        borderWidth: 1,
                        titleColor: 'rgba(255,255,255,0.85)',
                        bodyColor: '#1a9e7e',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: ctx => {
                                const real = values[ctx.dataIndex];
                                return `  Score : ${real > 0 ? '' : ''}${real} pts`;
                            }
                        }
                    }
                }
            }
        });
    }

});
