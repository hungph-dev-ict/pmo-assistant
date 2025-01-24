# Run on local
1. Copy .env.example to .env
2. Copy AdminLTE-3.2.0\plugins to resources/js
3. Set up .env params  
DB_CONNECTION=mysql  
DB_HOST=db  
DB_PORT=3306  
DB_DATABASE=pmo_assistant  
DB_USERNAME=admin  
DB_PASSWORD=pmo123456  
4. composer install
5. php artisan key:generate
6. npm install
7. docker pull hungph1996/pmo-assistant:latest
8. npm run build
9. npm run dev
10. docker-compose up -d --build
11. Run command in the below section **In container**

# Re-build container on Docker Hub
1. docker buildx build --platform linux/amd64,linux/arm64 -t hungph1996/pmo-assistant:latest --push .

# Re-pull container on Docker Hub
1. docker pull hungph1996/pmo-assistant:latest
2. docker-compose down    # Dừng các container đang chạy
3. docker-compose up -d   # Tạo lại và khởi động lại container mới từ image mới

# In container
1. docker exec -it web-app bash 
2. php artisan migrate
3. php artisan db:seed
4. php artisan optimize

# Git Flow

## Terminal in local
git pull origin develop --rebase  
composer install  
npm run build  
npm run dev  

## In container
php artisan migrate  
php artisan db:seed  
php artisan optimize:clear  

# setting json for Visual Studio Code
```
{
    // Formatter mặc định
    "editor.defaultFormatter": "esbenp.prettier-vscode",

    // Formatter cho file PHP
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
    },

    // Formatter cho file Blade
    "[blade]": {
        "editor.defaultFormatter": "shufo.vscode-blade-formatter"
    },

    // Tùy chọn Blade Formatter
    "bladeFormatter.format.wrapLineLength": 120, // Độ dài dòng tối đa
    "bladeFormatter.format.indentSize": 4,      // Kích thước thụt dòng
    "bladeFormatter.format.useTabs": false,     // Sử dụng spaces thay vì tabs

    // Tùy chọn PHP Formatter
    "intelephense.format.enable": true,         // Bật định dạng PHP
}
```
