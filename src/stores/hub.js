import { defineStore } from 'pinia';
import Axios from 'axios';

export const useHubStore = defineStore({
  id: 'store-hub',
  state: () => ({
    hubSettings: [],
  }),
  actions: {
    async fetchHubSettings() {
      try {
        const response = await Axios.get(msoAdminLocalizer.apiUrl + '/mso/v1/hub-settings');
        this.hubSettings = response.data;
      } catch (error) {
        console.error('Error fetching orders:', error);
      }
    },
  },
});