FROM ubuntu/nginx:1.18-22.04_beta

RUN apt-get update && apt-get upgrade -y

RUN apt-get install \
  php8.1 \
  php8.1-common \
  php8.1-curl \
  php8.1-fpm \
  php8.1-gd \
  php8.1-mbstring \
  php8.1-mysql \
  php8.1-xml \
  php8.1-zip -y

# Enable environment variables for PHP-FPM
RUN sed -i 's/;clear_env = no/clear_env = no/' /etc/php/8.1/fpm/pool.d/www.conf

# Enable verbose error messages
RUN sed -i 's/display_errors = Off/display_errors = On/' /etc/php/8.1/fpm/php.ini

# Copy nginx site configuration
COPY docker/nginx/default /etc/nginx/sites-available/default

# Restart nginx
RUN service nginx restart

# Switch to /var/www/html
WORKDIR /var/www/html

# Copy and run startup script
COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh
CMD ["/startup.sh"]
