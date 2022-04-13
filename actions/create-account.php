<?php
/**
 * Tin Can create account handler.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require '../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

require 'class-tc-json-response.php';

$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$field_names = array('username', 'email', 'password');

$filtered_fields = array();
$errors = array();

foreach ($field_names as $name) {
    if (isset($_POST[$name]) && !empty($_POST[$name])) {
        $filtered_fields[$name] = filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
    } else {
        $errors[$name] = TCObject::ERR_EMPTY_FIELD;
    }
}

$saved_user = null;

if (empty($errors)) {
    $db = new TCData();

    $settings = $db->load_settings();

    $user = new TCUser();

    $user->username = $filtered_fields['username'];
    $user->email = $filtered_fields['email'];
    $user->password = $user->get_password_hash($filtered_fields['password']);
    $user->role = $settings['default_user_role'];
    $user->created_time = time();
    $user->updated_time = time();

    $saved_user = $db->save_object($user);
}

// Verify user has been created.
if (empty($saved_user)) {
    $errors['username'] = TCObject::ERR_NOT_SAVED;
}

if (empty($errors)) {
    // Successfully created account. Create the user's session.
    $session = new TCUserSession();
    $session->create_session($user);
}

if (!empty($ajax)) {
    $response = new TCJSONResponse();

    $response->success = (empty($errors));
    $response->errors = $errors;

    exit($response->get_output());
} else {
    $destination = '/index.php?page=' . $settings['page_create_account'];

    if (!empty($errors)) {
        // TODO: Create a utility class for this.
        foreach ($errors as $name => $value) {
            $destination .= "&{$name}={$value}";
        }
    }

    header('Location: ' . $destination);
    exit;
}
