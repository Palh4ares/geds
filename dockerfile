FROM php:8.2-cli

# Instalar extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y unzip git curl

# Copiar projeto
COPY . /app
WORKDIR /app

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php

# Instalar dependências do Laravel
RUN php composer.phar install --no-dev --optimize-autoloader

# Permissões (importante)
RUN chmod -R 777 storage bootstrap/cache

# Rodar app
CMD php artisan migrate:fresh --force && php artisan serve --host=0.0.0.0 --port=$PORT