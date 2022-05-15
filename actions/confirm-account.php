<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCObject;
use TinCan\TCPendingUser;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can confirm account handler.
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

$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

$pending_user = new TCPendingUser();

$pending_results = $db->load_objects($pending_user, [], [['field' => 'confirmation_code', 'value' => $code]]);

if (!empty($pending_results)) {
  $pending_user = reset($pending_results);
}
else {
  $error = TCObject::ERR_NOT_FOUND;
}

$user = $db->load_user($pending_user->user_id);

if (empty($user)) {
  $error = TCObject::ERR_NOT_FOUND;
}

if (empty($error)) {
  // Successfully confirmed account. Create the user's session.
  $session = new TCUserSession();
  $session->create_session($user);

  // Delete the pending user record.
  $db->delete_object($pending_user, $pending_user->pending_user_id);

  // Send the user to the forum homepage.
  header('Location: '.TCURL::create_url(null));
  exit;
}
else {
  echo 'Unable to confirm your account. Please check the link in your email.';
}
