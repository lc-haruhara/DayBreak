import { defineConfig } from 'vite';
import path from 'node:path';

function wordpressPhpFullReload() {
  return {
    name: 'wordpress-php-full-reload',
    handleHotUpdate({ file, server }) {
      if (file.endsWith('.php')) {
        server.ws.send({
          type: 'full-reload',
        });
        return [];
      }
    },
  };
}

export default defineConfig({
  plugins: [wordpressPhpFullReload()],
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    watch: {
      usePolling: true,
      interval: 300,
    },
    hmr: {
      host: 'localhost',
      clientPort: 5173,
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        loadPaths: [
          path.resolve(__dirname, 'resource/scss'),
          path.resolve(__dirname, 'pages'),
          path.resolve(__dirname, 'components'),
        ],
      },
    },
  },
  build: {
    manifest: true,
    outDir: 'dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'resource/js/app.js'),
      },
    },
  },
});