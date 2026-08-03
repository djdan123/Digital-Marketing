import api from '../utils/api';
import { setToken, setUser, logout } from '../utils/auth';

export const login = async (credentials) => {
  const response = await api.post('/login', {
    ...credentials,
    device_name: 'TruckAll Dashboard',
  });
  const { token, user } = response.data;

  setToken(token);
  setUser(user);

  return user;
};

export const fetchProfile = async () => {
  const response = await api.get('/user');
  setUser(response.data);
  return response.data;
};

export const logoutApi = async () => {
  await api.post('/logout');
  logout();
};
