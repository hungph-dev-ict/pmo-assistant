import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // 'resources/css/adminlte.css', // CSS của AdminLTE
                // 'resources/js/adminlte.js',  // JS của AdminLTE
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            'admin-lte': path.resolve(__dirname, 'node_modules/admin-lte'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (/\.png|\.jpg|\.jpeg|\.svg$/.test(assetInfo.name)) {
                        return 'images/[name][extname]'; // Đưa hình ảnh vào thư mục `images`
                    }
                    return '[name][extname]';
                },
            },
        },
    },
});
