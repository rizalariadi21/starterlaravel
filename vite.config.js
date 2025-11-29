// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/apple/styles.scss',
        'resources/js/app.js'
      ],
      refresh: true,
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  define: {
    global: {}, // optional, fixes some libs expecting `global`
  },
  optimizeDeps: {
    include: ['jquery'],
  },
});