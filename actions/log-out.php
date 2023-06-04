<?php

use TinCan\template\TCURL;
use TinCan\user\TCUserSession;

/**
 * Tin Can log out handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';


// Destroy the user's session. Goodbye.
$session = new TCUserSession();
$session->start_session();
$session->destroy_session();

$destination = TCURL::create_url(null);

header('Location: '.$destination);
exit;
