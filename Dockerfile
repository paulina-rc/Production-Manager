FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd mbstring xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite (útil si usas rutas amigables)
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio raíz del proyecto dentro del contenedor
WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . .

# Instalar dependencias de PHP (phpmailer, phpspreadsheet, dompdf, etc.)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar Apache para escuchar en el puerto que Render asigna
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]