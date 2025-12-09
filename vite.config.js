import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/dashboard.js',
                'resources/js/pages/projects-show.js',
                'resources/js/pages/reports-index.js',
                'resources/js/pages/reports-by-career.js',
                'resources/js/pages/reports-by-period.js',
            ],
            refresh: true,
        }),
    ],
});
