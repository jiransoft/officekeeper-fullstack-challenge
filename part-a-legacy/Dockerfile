FROM php:8.3-apache

# PHP 확장 설치
RUN apt-get update && apt-get install -y unzip git \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer 설치
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache 모듈 활성화
RUN a2enmod rewrite

# DocumentRoot 설정
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# .htaccess 허용
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 소스 복사 및 의존성 설치
COPY . /var/www/html/
WORKDIR /var/www/html
RUN composer install --no-dev --no-interaction

# .htaccess 생성
RUN echo 'RewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^(.*)$ index.php/$1 [L]' > /var/www/html/public/.htaccess

# 권한 설정
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
