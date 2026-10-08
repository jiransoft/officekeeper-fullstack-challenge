FROM php:8.1-apache

# PHP 확장 설치
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache 모듈 활성화
RUN a2enmod rewrite

# DocumentRoot 설정
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# .htaccess 허용
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 소스 복사
COPY . /var/www/html/

# .htaccess 생성
RUN echo 'RewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^(.*)$ index.php/$1 [L]' > /var/www/html/public/.htaccess

# 권한 설정
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
