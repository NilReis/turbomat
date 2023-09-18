# Use a imagem PHP apropriada
FROM php:8.1-fpm

# Instale as extensões necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copie sua aplicação Laravel para o contêiner
COPY . /var/www/html

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Instale as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
&& docker-php-ext-install zip

RUN composer install

CMD ["php-fpm"]
