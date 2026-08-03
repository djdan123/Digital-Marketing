import api from '../utils/api';
import { getUser } from '../utils/auth';

const getCampaignBasePath = () => {
  const user = getUser();
  if (!user) {
    return '/admin';
  }

  const role = user.role?.toLowerCase() || '';
  if (role.includes('annonceur') || role.includes('advertiser')) {
    return '/advertiser';
  }

  return '/admin';
};

export const fetchCampaigns = async (params = {}) => {
  const response = await api.get(`${getCampaignBasePath()}/campaigns`, { params });
  return response.data;
};

export const fetchCampaign = async (id) => {
  const response = await api.get(`${getCampaignBasePath()}/campaigns/${id}`);
  return response.data;
};

export const createCampaign = async (payload) => {
  const response = await api.post(`${getCampaignBasePath()}/campaigns`, payload);
  return response.data;
};

export const updateCampaign = async (id, payload) => {
  const response = await api.put(`${getCampaignBasePath()}/campaigns/${id}`, payload);
  return response.data;
};

export const deleteCampaign = async (id) => {
  const response = await api.delete(`${getCampaignBasePath()}/campaigns/${id}`);
  return response.data;
};
