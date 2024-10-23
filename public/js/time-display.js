document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.convert-time').forEach(function (element) {
        const utcTime = element.getAttribute('data-time');
        const date = new Date(utcTime);
        element.textContent = date.toLocaleString([], {
            year: 'numeric', month: 'numeric', day: 'numeric',
            hour: '2-digit', minute: '2-digit', hour12: false
        });
    });
});
