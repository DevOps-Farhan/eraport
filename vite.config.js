import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const shouldLoadFonts = process.env.DISABLE_FONT_DOWNLOAD !== 'true';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: shouldLoadFonts
                ? [
                      bunny('Instrument Sans', {
                          weights: [400, 500, 600],
                      }),
                  ]
                : [],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
