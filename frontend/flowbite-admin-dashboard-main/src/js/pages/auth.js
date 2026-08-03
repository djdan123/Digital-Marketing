import { getToken, getUser } from '../utils/auth';

const pathname = window.location.pathname;
const publicRoutes = ['/authentication/', '/pages/', '/playground/'];
const isPublicPage = publicRoutes.some((path) => pathname.startsWith(path));
const landingPage = '/crud/campaigns/';

const token = getToken();

if (!isPublicPage) {
  if (!token) {
    window.location.href = '/authentication/sign-in/';
  }
} else if (pathname === '/authentication/sign-in/' && token) {
  window.location.href = landingPage;
}

const user = getUser();
if (user) {
  document.documentElement.dataset.userRole = user.role;
}
