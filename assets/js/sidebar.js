document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');

    if (!toggle || !sidebar) {
        return;
    }

    function closeSidebar() {
        sidebar.classList.remove('sidebar--open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    function openSidebar() {
        sidebar.classList.add('sidebar--open');
        toggle.setAttribute('aria-expanded', 'true');
    }

    toggle.addEventListener('click', function (event) {
        event.stopPropagation();

        if (sidebar.classList.contains('sidebar--open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    document.addEventListener('click', function (event) {
        if (!sidebar.classList.contains('sidebar--open')) {
            return;
        }

        if (sidebar.contains(event.target) || toggle.contains(event.target)) {
            return;
        }

        closeSidebar();
    });
});
