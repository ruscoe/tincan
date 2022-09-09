# Tin Can Forum

Tin Can is a lightweight web forum written in PHP. Named after the
[tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone),
this software is designed around functionality and simplicity.

Tin Can is still in early development.

## Goals

* Completely free and open
* No bloat
* Functional if JavaScript and / or style sheets are disabled

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

Depending on your version of nginx, the second line might be missing. Note that you can also
place your site configuration file directly into /etc/nginx/conf.d/ if you want to. Just
rename it from tincan.local to tincan.conf.

Continue on to the Configuration section.

## Configuration

Copy *tc-config-example.php* to *tc-config.php*

`cp tc-config-example.php tc-config.php`

Edit *tc-config.php* and change these values to suit your environment.

| Property        | Example Value              |
|-----------------|----------------------------|
| TC_BASE_PATH    | /var/www/YOUR_SITE         |
| TC_UPLOADS_PATH | /var/www/YOUR_SITE/uploads |
| TC_DB_HOST      | http://localhost           |
| TC_DB_USER      | root                       |
| TC_DB_PASS      | root                       |
| TC_DB_NAME      | tincan                     |

## Installation

Access your MySQL instance and create a new database.

`mysql> create database tincan;`

Be sure to set *TC_DB_NAME* in your configuration file if you choose
a different database name.

In your browser, open the URL you've set up for Tin Can forum.
You will be taken to the installer.

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

[MIT](https://mit-license.org). Do whatever you want.

Please send questions and comments to danruscoe@protonmail.com

___
"An idiot admires complexity, a genius admires simplicity" - Terry A. Davis
___
