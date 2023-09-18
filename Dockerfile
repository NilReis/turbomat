# Use a imagem PHP apropriada
FROM php:8.0-fpm

# Instale as extensões necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Copie sua aplicação Laravel para o contêiner
COPY . /var/www/html

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Instale as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD ["php-fpm"]
