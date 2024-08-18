# Tin Can Forum

Tin Can is a lightweight web forum written in PHP.

The name comes from the [tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone).

![Tin Can screenshot](https://github.com/user-attachments/assets/9bf5ef88-b441-4053-865e-b389aca8f839)

## Features

* Discussion boards groupable by topic
* Basic markdown support
* Image attachments
* Custom themes
* Extensive behat test suite

## Quick Start With Docker

Get started quickly with [Docker](https://www.docker.com/), [Docker Compose](https://docs.docker.com/compose/), and [Composer](https://getcomposer.org/).

Clone this git repository:

```bash
git clone git@github.com:ruscoe/tincan.git
cd tincan
```

Install requirements:

`composer install`

Start the Docker containers:

`docker compose up`

Run the installer via your web browser at:

`http://localhost/install.php`

If you ever want to connect to the Docker container running the web server, run:

`docker exec -it tincan-web-1 /bin/bash`

## Deploying to a web server

You'll need a web server running PHP 8.1 or above with the following extensions:

* curl
* fpm
* gd
* mbstring
* mysql
* xml
* zip

Run composer without development requirements:

`composer install --no-dev`

Set the following environment variables. Consult your web server documentation
on environment variables for more information.

| Variable        | Description                              | Example               |
|-----------------|------------------------------------------|-----------------------|
| TC_BASE_PATH    | The path where Tin Can files are located | /var/www/html         |
| TC_UPLOADS_PATH | The path for user uploaded files         | /var/www/html/uploads |
| TC_DB_HOST      | The address of your database server      |                       |
| TC_DB_USER      | The username of your database user       |                       |
| TC_DB_PASS      | The password of your database user       |                       |
| TC_DB_NAME      | The name of your database                | tincan                |
| TC_DB_PORT      | The open port of your database server    | 3306                  |

Set the permissions of the path defined in `TC_UPLOADS_PATH` so that files may be written.
For example:

```
chown -R www-data:www-data /var/www/html/uploads;
chmod -R 755 /var/www/html/uploads;
```

## Installation

![Tin Can installer screenshot](https://github.com/user-attachments/assets/c1233d2a-ca7b-4325-a184-d7d6cc02c784)

Check the "Generate test data" checkbox if you'd like to start with sample
users, boards, threads, and posts. This is useful for testing your new forum.

Click the "Install Tin Can Forum" button to install the forum and automatically
log in to the admin account.

## Custom Themes

See themes/README.md

## Running Tests

First, connect to the Docker container by running:

`docker exec -it tincan-web-1 /bin/bash`

For unit tests, run:

`./vendor/bin/phpunit`

For feature tests (behat), run:

`./vendor/bin/behat`

## License

[MIT](https://mit-license.org)
