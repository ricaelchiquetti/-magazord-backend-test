# Estágio 1: Instalando PHP e Composer
FROM php:8.3-apache AS php_installer

# Instalação do Composer
RUN apt-get update && apt-get install -y \
    curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Estágio 2: Construindo a aplicação PHP
FROM php_installer AS builder
WORKDIR /usr/src/magazord-backend
COPY . .

# Instalação de pacotes adicionais necessários para a aplicação
RUN apt-get update \
    && apt-get install -y \
    zip \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Definindo a variável de ambiente para permitir a execução do Composer como superusuário
ENV COMPOSER_ALLOW_SUPERUSER 1

# Instalação das dependências do Composer
RUN composer install

# Estágio 3: Preparando o banco de dados PostgreSQL
FROM postgres:latest AS postgres_setup
COPY --from=builder /usr/src/magazord-backend/database/migrations/create_tables.sql /docker-entrypoint-initdb.d/

# Estágio 4: Configurando o servidor PHP
FROM php_installer AS php_server
COPY --from=builder /usr/src/magazord-backend /var/www/html
WORKDIR /var/www/html

# Instalação do driver PDO para PostgreSQL
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql pdo

# Instalando o Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini 

# Comando padrão para iniciar o servidor PHP
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]
