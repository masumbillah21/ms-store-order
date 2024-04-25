import { defineStore } from 'pinia';
import Axios from 'axios';

export const useStoreStore = defineStore({
  id: 'store-store',
  state: () => ({
    storeSettings: [],
  }),
  actions: {
    async fetchStoreSettings() {
      try {
        const response = await Axios.get(msoAdminLocalizer.apiUrl + '/mso/v1/store-settings');
        this.storeSettings = response.data;
      } catch (error) {
        console.error('Error fetching orders:', error);
      }
    },
  },
});