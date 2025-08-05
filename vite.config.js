import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        vue({
            // Asegúrate de que el plugin de Vue esté configurado para manejar archivos Vue
            // También puedes añadir opciones aquí si es necesario, pero por defecto suele ser suficiente
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        hmr: {
            host: 'localhost', // O tu IP si estás en red
            port: 5173,
        },
    },
});