import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
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
      input: {
        'select2-min-css': 'resources/js/plugins/select2/css/select2.min.css',
        'select2-bootstrap4-css': 'resources/js/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
        'select2-full-js': 'resources/js/plugins/select2/js/select2.full.min.js',
        // 'moment-js': 'resources/js/plugins/moment/moment-with-locales.min.js',
        'tempusdominus-bootstrap-4-css': 'resources/js/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'tempusdominus-bootstrap-4-js': 'resources/js/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
      },
      output: {
        format: 'cjs', // Chuyển định dạng xuất sang CommonJS
        // Đưa các tệp JS vào thư mục `js/plugins`
        entryFileNames: 'js/plugins/[name].js',
        chunkFileNames: 'js/plugins/[name].js', // Nếu có chunk JS, sẽ đưa vào thư mục `js/plugins`
        assetFileNames: (assetInfo) => {
          // Xử lý các tệp CSS và hình ảnh
          if (/\.css$/.test(assetInfo.name)) {
            return 'css/plugins/[name][extname]'; // CSS đưa vào thư mục `css/plugins`
          }
          if (/\.png|\.jpg|\.jpeg|\.svg$/.test(assetInfo.name)) {
            return 'images/[name][extname]'; // Hình ảnh đưa vào thư mục `images`
          }
          return '[name][extname]'; // Các tệp khác giữ nguyên tên
        },
      },
    },
  },
});
