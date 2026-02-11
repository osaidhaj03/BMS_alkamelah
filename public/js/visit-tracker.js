/**
 * Visit Duration Tracker
 * يحسب مدة بقاء الزائر في الصفحة ويرسلها عند المغادرة
 */
(function () {
    'use strict';

    // نحصل على visit_id من الصفحة
    var visitId = document.querySelector('meta[name="visit-id"]');
    if (!visitId) return;
    visitId = visitId.getAttribute('content');
    if (!visitId || visitId === '') return;

    var startTime = Date.now();
    var sent = false;

    // نحصل على CSRF token
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

    function sendDuration() {
        if (sent) return;
        sent = true;

        var duration = Math.round((Date.now() - startTime) / 1000);

        // تجاهل الأوقات غير المنطقية (أقل من ثانية أو أكثر من 30 دقيقة)
        if (duration < 1 || duration > 1800) return;

        var data = JSON.stringify({
            visit_id: visitId,
            duration: duration
        });

        // نستخدم sendBeacon لأنه يعمل حتى عند إغلاق الصفحة
        if (navigator.sendBeacon) {
            var blob = new Blob([data], { type: 'application/json' });
            navigator.sendBeacon('/visit-duration', blob);
        } else {
            // fallback لمتصفحات قديمة
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/visit-duration', false); // synchronous
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.send(data);
        }
    }

    // عند مغادرة الصفحة
    window.addEventListener('beforeunload', sendDuration);

    // عند إخفاء الصفحة (تبديل تبويبة أو قفل الشاشة)
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            sendDuration();
        }
    });

    // عند التنقل عبر التاريخ (SPA navigation)
    window.addEventListener('pagehide', sendDuration);
})();
