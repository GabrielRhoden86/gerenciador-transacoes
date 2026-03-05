FROM php:8.2-fpm

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Sao_Paulo

# 1. Instala dependências do sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath mbstring xml \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY .env.example .env

# 2. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copia as dependências (Sem o prefixo 'backend/')
# Como o Dockerfile já está na pasta, o arquivo está "ao lado" dele
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

# 4. Copia o restante do código da pasta backend ----
COPY . .

# 5. Otimiza o Autoload e ajusta permissões
RUN composer dump-autoload --optimize \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
