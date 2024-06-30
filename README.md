# Tin Can Forum

Tin Can is a hobby project; an attempt to create a lightweight web forum in PHP.

The name comes from the [tin can telephone](https://en.wikipedia.org/wiki/Tin_can_telephone).

Tin Can is not production-ready, but you can play around with it if you like.

![Tin Can screenshot](https://user-images.githubusercontent.com/87952/231024890-7c25b40e-147e-43ab-aded-d139e5f09518.png)

![Tin Can screenshot](https://user-images.githubusercontent.com/87952/231024931-3e36e90c-a3b2-4d40-a3de-d733b568c477.png)

## TODO

* Fix avatar uploads
* More secure installer; stop relying on install.php being deleted post-install

## Requirements

Default configuration is provided in docker-compose.yml.

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) (if you want to create or edit themes)

## Quick Start

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

Run in the root directory:

`phpunit`

## License

[MIT](https://mit-license.org)
