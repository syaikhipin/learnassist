import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/webify/webify.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].min.js`,
                chunkFileNames: `assets/[name].min.js`,
                assetFileNames: `assets/[name].min.[ext]`,
                manualChunks: {},
            },
        },
    },
    esbuildOptions: {
        legalComments: 'none',
    },
});
