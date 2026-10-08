FROM php:8.1-apache

# PHP 확장 설치
RUN apt-get update && apt-get install -y \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install pdo pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer 설치
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache 설정
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 소스 복사 및 의존성 설치
COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction 2>/dev/null || true

# 권한 설정
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage 2>/dev/null || true \
    && chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true

EXPOSE 80
