<?php

use TinCan\controllers\TCUserController;
use TinCan\template\TCURL;

/**
 * Tin Can log out handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$controller = new TCUserController();

$controller->log_out();

$destination = TCURL::create_url(null);

header('Location: '.$destination);
exit;
