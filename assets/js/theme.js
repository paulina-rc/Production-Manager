function toggleTheme() {
    var html = document.documentElement;
    var current = html.getAttribute('data-theme');
    var next = current === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateToggleIcon(next);
}

function updateToggleIcon(theme) {
    var icon = document.getElementById('themeIcon');
    if (!icon) return;

    if (theme === 'dark') {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var theme = localStorage.getItem('theme') || 'light';
    updateToggleIcon(theme);
});
