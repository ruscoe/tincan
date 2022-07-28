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

* Composer (if you want to run unit tests)
* PHP 7.4.0 or above
* MySQL

## TODO: Running on nginx

## TODO: Running on Apache

## Configuration

Copy tc-config-example.php to tc-config.php and change these values to suit your environment.

* `TC_BASE_PATH` = `/var/www/YOUR_SITE`
* `TC_UPLOADS_PATH` = `/var/www/YOUR_SITE/uploads`
* `TC_DB_HOST` = `http://localhost`
* `TC_DB_USER` = `root`
* `TC_DB_PASS` = `root`
* `TC_DB_NAME` = `tincan`

## TODO: Installation

## TODO: Custom Themes

## Running Tests

Copy phpunit-example.xml to phpunit.xml and change the `const` values to match
those in the **Configuration** section.

### I have PHPUnit installed globally
Run `phpunit` in the root directory (phpunit.xml should exist here.)

### I just want PHPUnit for this project
In the root directory, run the following:
```
composer install
./vendor/bin/phpunit
```

## License

[MIT](https://mit-license.org). Do whatever you want.

Please send questions and comments to danruscoe@protonmail.com

___
"An idiot admires complexity, a genius admires simplicity" - Terry A. Davis
___
