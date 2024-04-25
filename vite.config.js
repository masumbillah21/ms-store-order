// vite.config.js
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  build: {
    outDir: 'assets',
    assetsDir: '',
    cssCodeSplit: true,
    rollupOptions: {
      input: {
        main: './src/main.js',
      },
      output: {
        entryFileNames: 'js/main.js',
        assetFileNames: 'css/main.css',
      },
    },
  },
});
