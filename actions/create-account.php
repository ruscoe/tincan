<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require 'class-tc-json-response.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$response = new TCJSONResponse();

$db = new TCData();

$user = new TCUser();

$user->username = $username;
$user->email = $email;
$user->password = $user->get_password_hash($password);
$user->created_time = time();
$user->updated_time = time();

$saved_user = $db->save_object($user);

// Verify user has been created.
if (empty($saved_user)) {
  $response->message = 'Unable to create your account at this time.';
  exit($response->get_output());
}

// Successfully created account. Create the user's session.
$session = new TCUserSession();
$session->create_session($user);

// $response->success = true;
// exit($response->get_output());

header('Location: /');
exit;
