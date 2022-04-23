<?php

use TinCan\TCURL;
use TinCan\TCUserSession;

/**
 * Tin Can log out handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

// Destroy the user's session. Goodbye.
$session = new TCUserSession();
$session->start_session();
$session->destroy_session();

$destination = TCURL::create_url(null);

header('Location: ' . $destination);
exit;
