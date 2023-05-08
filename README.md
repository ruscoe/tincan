# Tin Can Forum

Tin Can is a hobby project; an attempt to create a lightweight web forum in PHP.

The name comes from the [tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone).

Tin Can is not production-ready, but you can play around with it if you like.

![Screenshot from 2023-04-10 17-29-37](https://user-images.githubusercontent.com/87952/231024890-7c25b40e-147e-43ab-aded-d139e5f09518.png)

![Screenshot from 2023-04-10 17-30-06](https://user-images.githubusercontent.com/87952/231024931-3e36e90c-a3b2-4d40-a3de-d733b568c477.png)

## Requirements

* A Linux web server or compatible
* PHP 7.4.0 or above
* MySQL or compatible (MariaDB will probably work, too)
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org/) (if you want to create or edit themes)

## Install Requirements via Composer

If you don't already have it, install Composer according to the [directions here](https://getcomposer.org/download/).

In the directory you checked out this repository to, run:

`composer install`

## Configuration

Copy *tc-config-example.php* to *tc-config.php*

`cp tc-config-example.php tc-config.php`

Edit *tc-config.php* and change these values to suit your environment.

| Property               | Example Value              |
|------------------------|----------------------------|
| TC_BASE_PATH           | /var/www/html              |
| TC_UPLOADS_PATH        | /var/www/html/uploads      |
| TC_UPLOADS_PERMISSIONS | 0755                       |
| TC_DB_HOST             | 172.18.0.2                 |
| TC_DB_USER             | tincan                     |
| TC_DB_PASS             | changethis                 |
| TC_DB_NAME             | tincan                     |
| TC_DB_PORT             | 3306                       |

## Running with Docker

This is experimental.

### Create a network

`docker network create -d bridge tincannet`

### Set up MySQL

Start the MySQL container (make sure to change the passwords).

```
docker run \
--name tincan-mysql \
--net=tincannet \
-e MYSQL_ROOT_PASSWORD=changethis \
-e MYSQL_DATABASE=tincan \
-e MYSQL_USER=tincan \
-e MYSQL_PASSWORD=changethis \
-p 3308:3308 \
-d mysql:latest
```

If you'd like to access the container, run:

`docker exec -it tincan-mysql /bin/bash`

### Set up Tin Can

In the directory you checked out this repository to, run the following.

`docker build -t ruscoe/tincan .`

```
docker run -d \
--name tincan \
--net=tincannet \
-e TZ=UTC \
-p 8080:80 \
--mount type=bind,source="$(pwd)",target=/var/www/html \
ruscoe/tincan
```

If you'd like to access the container, run:

`docker exec -it tincan /bin/bash`

Run the installer via your web browser at:

`http://localhost:8080/install.php`

## Running on nginx

This example assumes your URL will be tincan.local and your root directory
is /var/www/tincan.local/html

To set this up, run the following:

`sudo mkdir /var/www/tincan.local/html`

`sudo mkdir /var/www/tincan.local/html/uploads`

Create an nginx configuration file:

`sudo nano /etc/nginx/sites-available/tincan.local`

Populate the configuration as below. Change anything that doesn't match
your environment to something that does. The PHP version likely needs to be
changed, unless you are running 7.4.

```
server {
  listen 80;
  listen [::]:80;

  root /var/www/tincan.local/html;
  index index.php;

  server_name tincan.local;

  if (!-e $request_filename) {
    rewrite ^.*$ /index.php last;
  }

  location / {
    try_files $uri $uri/ =404;
  }

  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
  }

  location ~ /\.ht {
    deny all;
  }
}
```

Enable your new configuration file by symlinking it:

`sudo ln -s /etc/nginx/sites-available/tincan.local /etc/nginx/sites-enabled/tincan.local`

Edit your hosts file:

`sudo nano /etc/hosts`

Add:

`127.0.0.1 tincan.local`

Finally, restart nginx:

`sudo service nginx restart`

### Create your Database

Access your MySQL instance and create a new database.

`mysql> create database tincan;`

Be sure to set *TC_DB_NAME* in your configuration file if you choose
a different database name.

## Installation

In your browser, open the URL you've set up for Tin Can forum. This will be
http://tincan.local if you've kept the default options so far.

You will be taken to the installer.

![Screenshot from 2023-04-10 17-28-50](https://user-images.githubusercontent.com/87952/231024993-e80b7bbc-879d-4d40-8c55-bb27731fba49.png)

Check the "Generate test data" checkbox if you'd like to start with sample
users, boards, threads, and posts. This is useful for testing your new forum.

Click the "Install Tin Can Forum" button to install the forum and automatically
log in to the admin account.

**DELETE install.php FROM YOUR SERVER AFTER INSTALLATION**

## Custom Themes

See themes/README.md

## Running Tests

Copy *phpunit-example.xml* to *phpunit.xml*

`cp phpunit-example.xml phpunit.xml`

Edit phpunit.xml and change the *const* values to match those in
the *Configuration* section.

Run in the root directory:

`phpunit`

## License

[MIT](https://mit-license.org).
