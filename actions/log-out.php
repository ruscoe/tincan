<?php
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

require 'class-tc-json-response.php';

// Destroy the user's session. Goodbye.
$session = new TCUserSession();
$session->start_session();
$session->destroy_session();

// $response = new TCJSONResponse();
// $response->success = true;
// exit($response->get_output());

header('Location: /index.php');
exit;
