FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm install

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build


FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# ადმინიდან სურათებისა და ვიდეოების ატვირთვა: PHP-ის ნაგულისხმევი ლიმიტი (2MB/8MB) ძალიან მცირეა.
# ვიდეო — 100MB-მდე, ერთ ფორმაში — 30 ფაილამდე.
RUN printf "upload_max_filesize=110M\npost_max_size=512M\nmax_file_uploads=35\nmax_execution_time=300\nmax_input_time=300\nmemory_limit=256M\n" > /usr/local/etc/php/conf.d/uploads.ini

# ადმინიდან ატვირთული ფაილები (storage/app/public) საიტზე /storage მისამართით.
RUN php artisan storage:link

RUN chown -R www-data:www-data storage bootstrap/cache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]