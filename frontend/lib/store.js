import { create } from 'zustand';
import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000/api';

const useAuthStore = create((set) => ({
  user: null,
  token: null,
  isLoading: false,
  error: null,

  setToken: (token) => {
    set({ token });
    if (typeof window !== 'undefined') {
      localStorage.setItem('token', token);
    }
  },

  register: async (username, email, password, fullName) => {
    set({ isLoading: true, error: null });
    try {
      const res = await axios.post(`${API_URL}/auth/register`, {
        username,
        email,
        password,
        fullName
      });
      set({ 
        user: res.data.user, 
        token: res.data.token,
        isLoading: false 
      });
      if (typeof window !== 'undefined') {
        localStorage.setItem('token', res.data.token);
      }
      return res.data;
    } catch (error) {
      const message = error.response?.data?.message || error.message;
      set({ error: message, isLoading: false });
      throw error;
    }
  },

  login: async (email, password) => {
    set({ isLoading: true, error: null });
    try {
      const res = await axios.post(`${API_URL}/auth/login`, {
        email,
        password
      });
      set({ 
        user: res.data.user, 
        token: res.data.token,
        isLoading: false 
      });
      if (typeof window !== 'undefined') {
        localStorage.setItem('token', res.data.token);
      }
      return res.data;
    } catch (error) {
      const message = error.response?.data?.message || error.message;
      set({ error: message, isLoading: false });
      throw error;
    }
  },

  logout: () => {
    set({ user: null, token: null });
    if (typeof window !== 'undefined') {
      localStorage.removeItem('token');
    }
  },

  loadUser: async () => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('token');
      if (token) {
        set({ token });
      }
    }
  }
}));

export default useAuthStore;
