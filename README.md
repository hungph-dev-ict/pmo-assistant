# Run on local
1. Copy .env.example to .env
2. Set up .env params  
DB_CONNECTION=mysql  
DB_HOST=db  
DB_PORT=3306  
DB_DATABASE=pmo_assistant  
DB_USERNAME=admin  
DB_PASSWORD=pmo123456  
3. composer install
4. php artisan key:generate
5. npm install
6. docker pull hungph1996/pmo-assistant:latest
7. npm run build
8. npm run dev
9. docker pull hungph1996/pmo-assistant:latest
10. docker-compose up -d
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