<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can password reset handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require 'class-tc-json-response.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
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

$error = (empty($email)) ? TCUser::ERR_NOT_FOUND : null;

if (empty($error)) {
  // Find user with matching email.
  $conditions = [
    [
      'field' => 'email',
      'value' => $email,
    ],
  ];

  try {
    $user_results = $db->load_objects(new TCUser(), [], $conditions);
    if (!empty($user_results)) {
      $user = reset($user_results);
    }
  } catch (TCException $e) {
    echo $e->getMessage();
    exit;
  }
}

if (empty($user)) {
  $error = TCUser::ERR_NOT_FOUND;
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the reset password page.
    // TODO: Add a success message.
    $destination = TCURL::create_url($settings['page_reset_password']);
  } else {
    // Send user back to the reset password page with an error.
    $destination = TCURL::create_url($settings['page_reset_password'], ['error' => $error]);
  }

  header('Location: '.$destination);
  exit;
}
