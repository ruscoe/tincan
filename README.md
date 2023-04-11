# Tin Can Forum

Tin Can is a hobby project; an attempt to create a lightweight web forum in PHP.

The name comes from the [tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone).

Tin Can is not production-ready, but you can play around with it if you like.

![Screenshot from 2023-04-10 17-29-37](https://user-images.githubusercontent.com/87952/231024890-7c25b40e-147e-43ab-aded-d139e5f09518.png)

![Screenshot from 2023-04-10 17-30-06](https://user-images.githubusercontent.com/87952/231024931-3e36e90c-a3b2-4d40-a3de-d733b568c477.png)

## Requirements

* [Composer](https://getcomposer.org)
* PHP 7.4.0 or above
* MySQL

## Running Tin Can Forum

For these examples, assume the URL will be tincan.local and your root directory
is /var/www/tincan.local/html

To set this up, run the following:

`sudo mkdir /var/www/tincan.local/html`

`sudo mkdir /var/www/tincan.local/html/uploads`

### Running on nginx

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

If you see the default nginx server when you check http://tincan.local, ensure your nginx
configuration contains the following:

```
include /etc/nginx/conf.d/*.conf;
include /etc/nginx/sites-enabled/*;
```

Depending on your version of nginx, the second line might be missing.

Continue on to the Configuration section.

## Configuration

Copy *tc-config-example.php* to *tc-config.php*

`cp tc-config-example.php tc-config.php`

Edit *tc-config.php* and change these values to suit your environment.

| Property               | Example Value              |
|------------------------|----------------------------|
| TC_BASE_PATH           | /var/www/YOUR_SITE         |
| TC_UPLOADS_PATH        | /var/www/YOUR_SITE/uploads |
| TC_UPLOADS_PERMISSIONS | 0755                       |
| TC_DB_HOST             | http://localhost           |
| TC_DB_USER             | root                       |
| TC_DB_PASS             | root                       |
| TC_DB_NAME             | tincan                     |

## Create your Database

Access your MySQL instance and create a new database.

`mysql> create database tincan;`

Be sure to set *TC_DB_NAME* in your configuration file if you choose
a different database name.

## Install Requirements via Composer

If you don't already have it, install Composer according to the [directions here](https://getcomposer.org/download/).

In the root directory, run:

`composer install`

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

## TODO

- Replace Compass with a more modern SASS compiler

## License

[MIT](https://mit-license.org).
