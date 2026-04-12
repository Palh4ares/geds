FROM php:8.2-cli

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar projeto
WORKDIR /app
COPY . .

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader

# Permissões Laravel
RUN chmod -R 775 storage bootstrap/cache

# Rodar app
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=$PORT