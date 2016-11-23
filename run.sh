docker stop videoupload
docker rm videoupload
docker run --name videoupload -d -e APP_ENV=dev -v $(pwd):/www/site -p 8085:80 -v $(pwd)/conf/nginx:/etc/nginx/sites-enabled/ richarvey/nginx-php-fpm:latest
