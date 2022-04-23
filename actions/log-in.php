<?php

use TinCan\TCData;
use TinCan\TCJSONResponse;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can log in handler.
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

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

$settings = $db->load_settings();

// Find user with matching username.
$conditions = [
  [
    'field' => 'username',
    'value' => $username,
  ],
];

$error = null;

$user = null;

$user_results = $db->load_objects(new TCUser(), [], $conditions);
if (!empty($user_results)) {
  $user = reset($user_results);
}

if (empty($user) || !$user->verify_password_hash($password, $user->password)) {
  $error = TCUser::ERR_NOT_FOUND;
}

if (empty($error)) {
  // Successfully logged in. Create the user's session.
  $session = new TCUserSession();
  $session->create_session($user);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the forum homepage.
    $destination = TCURL::create_url(null);
  } else {
    // Send user back to the log in page with an error.
    $destination = TCURL::create_url($settings['page_log_in'], ['error' => $error]);
  }

  header('Location: '.$destination);
  exit;
}
