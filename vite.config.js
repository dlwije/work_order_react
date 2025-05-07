import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),

    ],
    
    build: {
        minify: true, // 🚫 Disable minification
        // emptyOutDir: false, // ✅ Don't clear the build output directory
        sourcemap: true,
    },
    server: {
        hmr: true,
    },
});
