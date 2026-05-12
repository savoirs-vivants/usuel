document.addEventListener('DOMContentLoaded', function () {
    const qrcodeEl = document.getElementById('qrcode');

    if (qrcodeEl) {
        const targetUrl = qrcodeEl.dataset.url;
        if (targetUrl && typeof QRCode !== 'undefined') {
            new QRCode(qrcodeEl, {
                text: targetUrl,
                width: 80,
                height: 80,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    }
});
