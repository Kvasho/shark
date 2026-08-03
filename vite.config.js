import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/user/app.css',
                'resources/js/user/app.js',

                'resources/css/user/main.css',
                'resources/js/user/main.js',

                'resources/css/user/company.css',
                'resources/js/user/company.js',

                'resources/css/user/contact.css',
                'resources/js/user/contact.js',

                'resources/css/user/media.css',
                'resources/js/user/media.js',

                'resources/css/user/projects.css',
                'resources/js/user/projects.js',

                'resources/css/user/services.css',
                'resources/js/user/services.js',
            ],

            refresh: [
                'app/Http/Controllers/**',
                'app/Models/**',
                'resources/views/**',
                'routes/**',
            ],

            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),

        tailwindcss(),
    ],

    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});
