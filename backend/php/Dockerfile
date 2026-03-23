FROM php:8.4-fpm

# 1. Instalar dependencias y extensiones (Añadido bcmath y mbstring para Laravel)
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libxml2-dev libonig-dev libssl-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_mysql xml ctype zip bcmath \
    && pecl install mongodb xdebug \
    && docker-php-ext-enable mongodb xdebug \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Crear usuario app
RUN groupadd -g 1000 app && \
    useradd -u 1000 -g 1000 -m -s /bin/bash app

# 4. Preparar el script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# 5. EL TRUCO DEFINITIVO:
# El contenedor arranca como root para poder hacer el chown del volumen,
# y el script de entrada se encarga de que todo funcione.
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]