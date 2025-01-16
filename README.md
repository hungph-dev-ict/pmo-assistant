# Run on local
1. Copy .env.example to .env
2. composer install
3. php artisan key:generate
4. npm install
5. npm run build
6. docker-compose build
7. docker-compose up -d

# Re-build container on Docker Hub
1. docker build -t hungph1996/pmo-assistant:latest .
2. docker push hungph1996/pmo-assistant:latest

# Re-pull container on Docker Hub
1. docker pull hungph1996/pmo-assistant:latest
2. docker-compose down    # Dừng các container đang chạy
3. docker-compose up -d   # Tạo lại và khởi động lại container mới từ image mới

# In container
1. docker exec -it web-app bash 
2. php artisan migrate
3. php artisan db:seed