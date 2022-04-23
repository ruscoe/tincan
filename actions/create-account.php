<?php

use TinCan\TCData;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can create account handler.
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

$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
// Don't trim password. Spaces are permitted anywhere in the password.
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$db = new TCData();

$settings = $db->load_settings();

$user = new TCUser();

// Validate username.
if (!$user->validate_username($username)) {
  $error = TCUser::ERR_USER;
}
// Validate email.
if (empty($error) && !$user->validate_email($email)) {
  $error = TCUser::ERR_EMAIL;
}
// Validate password.
if (empty($error) && !$user->validate_password($password)) {
  $error = TCUser::ERR_PASSWORD;
}

// Check for existing username / email.
if (empty($error)) {
  $existing_user = $db->load_objects($user, [], [['field' => 'username', 'value' => $username]]);

  if (!empty($existing_user)) {
    $error = TCUser::ERR_USERNAME_EXISTS;
  }
}

if (empty($error)) {
  $existing_user = $db->load_objects($user, [], [['field' => 'email', 'value' => $email]]);

  if (!empty($existing_user)) {
    $error = TCUser::ERR_EMAIL_EXISTS;
  }
}

$saved_user = null;

if (empty($error)) {
  $user->username = $username;
  $user->email = $email;
  $user->password = $user->get_password_hash($password);
  $user->role_id = $settings['default_user_role'];
  $user->created_time = time();
  $user->updated_time = time();

  $saved_user = $db->save_object($user);

  // Verify user has been created.
  if (empty($saved_user)) {
    $error = TCObject::ERR_NOT_SAVED;
  }
}

if (empty($error)) {
  // Successfully created account. Create the user's session.
  $session = new TCUserSession();
  $session->create_session($user);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '/index.php';

  if (empty($error)) {
    // Send user to the forum homepage.
    $destination .= '?';
  } else {
    // Send user back to the create account page with an error.
    $destination .= '?page='.$settings['page_create_account']
    .'&username='.$username.'&email='.$email.'&error='.$error;
  }

  header('Location: '.$destination);
  exit;
}
