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

# 2. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- AJUSTE DE PERMISSÃO INICIAL ---
# Garante que o diretório de trabalho pertença ao usuário do servidor web antes de copiar os arquivos
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

# 3. Copia apenas arquivos de dependência primeiro (otimiza cache de camadas)
COPY --chown=www-data:www-data composer.json composer.lock ./

# Muda para o usuário www-data para instalar as dependências
USER www-data

RUN composer install --no-scripts --no-autoloader --prefer-dist

# 4. Copia o restante do código com a permissão correta
COPY --chown=www-data:www-data . .

# 5. Cria as pastas necessárias e limpa o cache
# O sinalizador -p garante que não dê erro se a pasta já existir
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --optimize

EXPOSE 9000

CMD ["php-fpm"]
