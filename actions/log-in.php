<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require 'class-tc-json-response.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$response = new TCJSONResponse();

$db = new TCData();

// Find user with matching username.
$conditions = array(
  array(
    'field' => 'username',
    'value' => $username
  )
);

$user = null;

$user_results = $db->load_objects(new TCUser(), array(), $conditions);
if (!empty($user_results)) {
  $user = reset($user_results);
}

// Verify user exists and entered password correctly.
if (empty($user) || !$user->verify_password_hash($password, $user->password)) {
  $response->message = 'Unable to log in at this time.';
  exit($response->get_output());
}

// Successfully logged in. Create the user's session.
$session = new TCUserSession();
$session->create_session($user);

$response->success = true;
exit($response->get_output());
