document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');
    const themeToggle = document.getElementById('theme-toggle');

    const closeSidebar = () => {
        sidebar?.classList.add('hidden');
        sidebarBackdrop?.classList.add('hidden');
    };

    const openSidebar = () => {
        sidebar?.classList.remove('hidden');
        sidebarBackdrop?.classList.remove('hidden');
    };

    const toggleSidebar = () => {
        if (sidebar?.classList.contains('hidden')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    };

    const toggleUserMenu = () => {
        userMenu?.classList.toggle('hidden');
    };

    sidebarToggle?.addEventListener('click', toggleSidebar);
    sidebarBackdrop?.addEventListener('click', closeSidebar);
    userMenuButton?.addEventListener('click', toggleUserMenu);
    themeToggle?.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('truckall-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
});
