FROM ubuntu/nginx:1.18-20.04_beta

RUN apt-get update && apt-get upgrade -y

RUN apt-get install \
  php7.4 \
  php7.4-common \
  php7.4-curl \
  php7.4-fpm \
  php7.4-mysql \
  php7.4-xml \
  php7.4-zip -y

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy nginx site configuration
COPY docker/nginx/default /etc/nginx/sites-available/default

# Restart nginx
RUN service nginx restart

# Switch to /var/www/html
WORKDIR /var/www/html

# Start PHP-FPM
CMD /etc/init.d/php7.4-fpm start -F && nginx -g "daemon off;"
