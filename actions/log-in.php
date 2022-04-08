<?php

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require 'class-tc-json-response.php';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

$settings = $db->load_settings();

// Find user with matching username.
$conditions = array(
  array(
    'field' => 'username',
    'value' => $username
  )
);

$user = null;

$errors = array();

$user_results = $db->load_objects(new TCUser(), array(), $conditions);
if (!empty($user_results)) {
  $user = reset($user_results);
}

if (empty($user)) {
  $errors['username'] = TCUser::ERR_NOT_FOUND;
}

if (empty($errors) && !$user->verify_password_hash($password, $user->password)) {
  $errors['password'] = TCUser::ERR_PASSWORD;
}

if (empty($errors)) {
  // Successfully logged in. Create the user's session.
  $session = new TCUserSession();
  $session->create_session($user);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($errors));
  $response->errors = $errors;

  exit($response->get_output());
}
else {
  $destination = '/index.php?page=' . $settings['page_log_in'];

  if (!empty($errors)) {
    // TODO: Create a utility class for this.
    foreach ($errors as $name => $value) {
      $destination .= "&{$name}={$value}";
    }
  }

  header('Location: ' . $destination);
  exit;
}
