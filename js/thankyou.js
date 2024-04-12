document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const date = urlParams.get('date');
    const time = urlParams.get('time');

    if (date && time) {
        document.getElementById('appointment-date').textContent = date;
        document.getElementById('appointment-time').textContent = time;
    } else {
        // Handle missing date/time or show a default message
    }
});
