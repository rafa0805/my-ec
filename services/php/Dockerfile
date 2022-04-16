FROM php:8.1-fpm

# 依存解決用のツール (コミュニティサポート)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Install PHP extentions.
# install-php-extensions is used resolve dependencies required for PHP extention installation
RUN apt-get update && \
    apt-get install -y \
        git && \
    install-php-extensions \
        gd \
        mbstring \
        pdo_mysql \
        xdebug \
        zip


# Copy composer executable binary from Composer official image
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf


    # docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    # docker-php-ext-install gd

# COPY ./php.ini-development /usr/local/etc/php/php.ini
