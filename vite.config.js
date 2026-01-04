import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',  // Cambiar de CSS a Sass
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});