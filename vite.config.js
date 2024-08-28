import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/components/map.css',
                'resources/js/app.js',
                'resources/js/components/map-polygon.js',
            ],
            refresh: true,
        }),
    ],
});
