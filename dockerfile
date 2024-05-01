# Estágio 1: Construindo a aplicação PHP
FROM php:8.2-cli AS builder

# Copie o código-fonte da aplicação para dentro do contêiner
COPY -magazord-backend-test /usr/src/magazord-backend

# Defina o diretório de trabalho
WORKDIR /usr/src/magazord-backend

# Instale as dependências da aplicação usando o Composer
RUN apt-get update && apt-get install -y \
    curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install

# Instale as extensões PHP necessárias
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Configurar o Xdebug
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/php.ini

# Estágio 2: Preparando o banco de dados PostgreSQL
FROM postgres:latest

# Copie o arquivo SQL do primeiro estágio para o contêiner PostgreSQL
COPY --from=builder /usr/src/magazord-backend/database/create_tables.sql /docker-entrypoint-initdb.d/

# Defina as permissões adequadas para o arquivo SQL
RUN chmod +x /docker-entrypoint-initdb.d/create_tables.sql
