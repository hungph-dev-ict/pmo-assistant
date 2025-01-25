import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { globSync } from 'glob'; // Thay thế cách import

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        ...globSync('resources/images/adminlte/**/*.{png,jpg,jpeg,svg,gif}'), // Thêm tất cả hình ảnh từ thư mục images/adminlte
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
        app: 'resources/js/app.js', // Điểm entry chính
        css: 'resources/css/app.css', // File CSS chính
        ...Object.fromEntries(
          globSync('resources/images/adminlte/**/*.*').map((file) => [
            file.replace('resources/', '').replace(/\//g, '-'),
            path.resolve(__dirname, file),
          ])
        ),
        'fontawesome-free-css': 'resources/js/plugins/fontawesome-free/css/all.min.css',
        'flag-icon-css': 'resources/js/plugins/flag-icon-css/css/flag-icon.min.css',
        'select2-min-css': 'resources/js/plugins/select2/css/select2.min.css',
        'select2-bootstrap4-css': 'resources/js/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
        'select2-full-js': 'resources/js/plugins/select2/js/select2.full.min.js',
        'tempusdominus-bootstrap-4-css': 'resources/js/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'tempusdominus-bootstrap-4-js': 'resources/js/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'sweetalert2-theme-bootstrap-4-css': 'resources/js/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
        'sweetalert2-js': 'resources/js/plugins/sweetalert2/sweetalert2.min.js',
        'toastr-css': 'resources/js/plugins/toastr/toastr.min.css',
        'toastr-js': 'resources/js/plugins/toastr/toastr.min.js',
        'bs-custom-file-input': 'resources/js/plugins/bs-custom-file-input/bs-custom-file-input.min.js'
      },
      output: {
        format: 'cjs', // Chuyển định dạng xuất sang CommonJS
        // Đưa các tệp JS vào thư mục js/plugins
        entryFileNames: 'js/plugins/[name].js',
        chunkFileNames: 'js/plugins/[name].js', // Nếu có chunk JS, sẽ đưa vào thư mục js/plugins
        assetFileNames: (assetInfo) => {
          // Xử lý các tệp CSS và hình ảnh
          if (/\.css$/.test(assetInfo.name)) {
            return 'css/plugins/[name][extname]'; // CSS đưa vào thư mục css/plugins
          }
          if (/\.png|\.jpg|\.jpeg|\.svg|\.gif$/.test(assetInfo.name)) {
            return 'images/plugins/[name][extname]'; // Hình ảnh đưa vào thư mục images/plugins
          }
          return '[name][extname]'; // Các tệp khác giữ nguyên tên
        },
      },
    },
  },
});
