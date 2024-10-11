# Usar a imagem base do PHP
FROM php:8.2-fpm

# Instalação das dependências do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install zip pdo mbstring bcmath opcache pdo_mysql

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /app

# Copiar arquivos do projeto
COPY . /app

# Ajustar permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Instalar as dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Copiar o script de espera
COPY start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

# Expor a porta 9000
EXPOSE 9000

# Comando de inicialização
CMD start db:3306 -- php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000
