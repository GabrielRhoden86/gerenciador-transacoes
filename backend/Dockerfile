FROM php:8.2-fpm

# 1. Instala dependências do sistema (Camada pesada - Cacheada)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite zip bcmath mbstring xml \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copia apenas arquivos de dependência primeiro (Acelera o Build)
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

# 4. Copia o restante do código
COPY backend/ .

# 5. CORREÇÃO: Gerar Autoload Corretamente e Ajustar Permissões
# Mudamos para dump-autoload e garantimos que o mkdir rode como root antes do USER
RUN composer dump-autoload --optimize \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
