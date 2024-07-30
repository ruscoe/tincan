# Tin Can Forum

Tin Can is a hobby project; an attempt to create a lightweight web forum in PHP.

The name comes from the [tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone).

Tin Can is not production-ready, but you can play around with it if you like.

![Tin Can screenshot](https://github.com/user-attachments/assets/3ba89a57-f60b-4f15-bd27-94cf39af62b1)

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

## Installation

![Tin Can installer screenshot](https://user-images.githubusercontent.com/87952/231024993-e80b7bbc-879d-4d40-8c55-bb27731fba49.png)

Check the "Generate test data" checkbox if you'd like to start with sample
users, boards, threads, and posts. This is useful for testing your new forum.

Click the "Install Tin Can Forum" button to install the forum and automatically
log in to the admin account.

**DELETE install.php FROM YOUR SERVER AFTER INSTALLATION**

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
