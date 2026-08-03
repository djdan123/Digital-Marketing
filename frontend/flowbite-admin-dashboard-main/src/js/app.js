import { initSidebar } from './components/sidebar';
import './pages/auth';

const routeScripts = {
  '/authentication/sign-in/': () => import('./pages/login'),
  '/crud/campaigns/': () => import('./pages/campaigns'),
};

const currentPath = window.location.pathname;

initSidebar();

Object.keys(routeScripts).forEach((route) => {
  if (currentPath.endsWith(route)) {
    routeScripts[route]();
  }
});
