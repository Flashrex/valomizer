import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from "@tailwindcss/vite";
import { resolve } from 'node:path';
import { defineConfig } from 'vite';
import fs from 'fs';

const appUrl = process.env.APP_URL || 'https://localhost';
const host = new URL(appUrl).hostname;
const appEnv = process.env.APP_ENV || 'local';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
            '@font': path.resolve(__dirname, './resources/fonts'),
        },
    },
    server: { 
        host, 
        hmr: { host }, 
        ...(appEnv !== 'local'
            ? {
                https: {
                    key: fs.readFileSync(`${process.env.HOME}/.ssl/privkey.pem`),
                    cert: fs.readFileSync(`${process.env.HOME}/.ssl/fullchain.pem`),
                }
            }
            : {}
        ),
    },
});
