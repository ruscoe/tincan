<?php

define('TC_VERSION', '0.09');

/* File paths * */

define('TC_BASE_PATH', '/var/www/YOUR_SITE');
define('TC_UPLOADS_PATH', '/var/www/YOUR_SITE/uploads');

/* Database server * */

define('TC_DB_CLASS', 'TinCan\TCMySQL');
define('TC_DB_HOST', 'http://localhost');
define('TC_DB_USER', 'root');
define('TC_DB_PASS', 'root');
define('TC_DB_NAME', 'tincan');

/* Mail server * */

// https://github.com/PHPMailer/PHPMailer#a-simple-example

define('TC_SMTP_HOST', 'smtp.example.com');
define('TC_SMTP_USER', 'user@example.com');
define('TC_SMTP_PASS', 'password');
define('TC_SMTP_PORT', '465');
// Enable implicit TLS encryption.
define('TC_SMTP_TLS', true);
// Enable verbose debug output.
define('TC_SMTP_DEBUG', false);
