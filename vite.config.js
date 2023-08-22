import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import * as path from "path";

export default defineConfig({
    /*build: {
        rollupOptions: {
            input: path.resolve(__dirname, 'resources/app.ts')
        },
        cssCodeSplit: true,
        //assetsDir: 'css'
    },*/
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
