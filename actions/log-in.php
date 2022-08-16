<?php

use TinCan\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCPendingUser;
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

require TC_BASE_PATH.'/core/class-tc-error-message.php';
require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-json-response.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

$error = null;
$user = null;

// Find user with matching username.
$user_results = $db->load_objects(new TCUser(), [], [['field' => 'username', 'value' => $username]]);
if (!empty($user_results)) {
  $user = reset($user_results);
}

if (!empty($user) && !$user->can_perform_action(TCUser::ACT_LOG_IN)) {
  $error = TCUser::ERR_NOT_FOUND;
}

if (!empty($user)) {
  // Check for pending user.
  $pending_user_results = $db->load_objects(new TCPendingUser(), [], [['field' => 'user_id', 'value' => $user->user_id]]);
  if (!empty($pending_user_results)) {
    // Pending users cannot log in until the account is confirmed.
    $error = TCUser::ERR_NOT_FOUND;
  }
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
  header('Content-type: application/json; charset=utf-8');

  $response = new TCJSONResponse();

  $response->success = (empty($error));

  if (!empty($error)) {
    $error_message = new TCErrorMessage();
    $response->errors = $error_message->get_error_message('log-in', $error);
  }

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
