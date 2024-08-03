import { defineConfig } from 'vite';
import path from 'path';
import { nodePolyfills } from 'vite-plugin-node-polyfills';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [nodePolyfills()],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~bootstrap-icons': path.resolve(
                __dirname,
                'node_modules/bootstrap-icons',
            ),
        },
    },
    build: {
        emptyOutDir: true,
        manifest: true,
        assetsDir: '',
        outDir: './webroot/dist',
        rollupOptions: {
            input: {
                app: './assets/main.ts',
                styles: './assets/styles/styles.scss',
            },
        },
    },
    server: {
        port: 3000,
        strictPort: true,
        watch: {
            usePolling: true,
        },
    },
});
