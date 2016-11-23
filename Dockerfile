FROM richarvey/nginx-php-fpm
EXPOSE 80
RUN rm -fr /etc/nginx/sites-available/* && rm -fr /etc/nginx/sites-enabled/*
COPY ./ /www/site
COPY ./conf/nginx /etc/nginx/sites-enabled
RUN mkdir -p /www/site/logs && chmod 777 /www/site/logs
CMD ["/start.sh"]
