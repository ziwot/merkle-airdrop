import { defineConfig } from 'vite';
import path from 'path';
import { nodePolyfills } from 'vite-plugin-node-polyfills'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        nodePolyfills(),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    build: {
        manifest: true,
        assetsDir: '',
        outDir: './webroot/dist',
        rollupOptions: {
            input: './assets/main.ts',
        }
    },
    server: {
        port: 3000,
        strictPort: true,
        watch: {
            usePolling: true
        }
    }
});
