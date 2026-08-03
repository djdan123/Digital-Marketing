import '../css/app.css';
import 'flowbite';

const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarBackdrop = document.getElementById('sidebarBackdrop');
const userMenuButton = document.getElementById('user-menu-button');
const userMenu = document.getElementById('user-menu');
const themeToggle = document.getElementById('theme-toggle');
const darkIcon = document.getElementById('theme-toggle-dark-icon');
const lightIcon = document.getElementById('theme-toggle-light-icon');

const setThemeIcons = () => {
    if (document.documentElement.classList.contains('dark')) {
        darkIcon?.classList.add('hidden');
        lightIcon?.classList.remove('hidden');
    } else {
        darkIcon?.classList.remove('hidden');
        lightIcon?.classList.add('hidden');
    }
};

const enableDarkMode = (dark) => {
    if (dark) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('truckall-theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('truckall-theme', 'light');
    }
    setThemeIcons();
};

const initTheme = () => {
    const storedTheme = localStorage.getItem('truckall-theme');
    if (storedTheme === 'dark') {
        enableDarkMode(true);
    } else if (storedTheme === 'light') {
        enableDarkMode(false);
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        enableDarkMode(true);
    } else {
        enableDarkMode(false);
    }
};

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
    if (userMenu) {
        userMenu.classList.toggle('hidden');
    }
};

window.addEventListener('DOMContentLoaded', () => {
    initTheme();

    sidebarToggle?.addEventListener('click', toggleSidebar);
    sidebarBackdrop?.addEventListener('click', closeSidebar);
    userMenuButton?.addEventListener('click', toggleUserMenu);
    themeToggle?.addEventListener('click', () => enableDarkMode(!document.documentElement.classList.contains('dark')));
});
