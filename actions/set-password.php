<?php

use TinCan\db\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\objects\TCObject;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;

/**
 * Tin Can set password handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';


$code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
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

$error = (empty($code)) ? TCObject::ERR_NOT_FOUND : null;

if (empty($error)) {
    // Find user with matching password reset code.
    $conditions = [
      [
        'field' => 'password_reset_code',
        'value' => $code,
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

if (empty($error)) {
    $user->password = $user->get_password_hash($password);
    // Password has been reset, so expire the reset code.
    $user->password_reset_code = '';

    $saved_user = $db->save_object($user);

    if (empty($saved_user)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

if (!empty($ajax)) {
    header('Content-type: application/json; charset=utf-8');

    $response = new TCJSONResponse();

    $response->success = (empty($error));

    if (!empty($error)) {
        $error_message = new TCErrorMessage();
        $response->errors = $error_message->get_error_message('set-password', $error);
    }

    exit($response->get_output());
} else {
    $destination = '';

    if (empty($error)) {
        // Send user to the set password page with a success message.
        $destination = TCURL::create_url($settings['page_set_password'], ['status' => 'set']);
    } else {
        // Send user back to the set password page with an error.
        $destination = TCURL::create_url($settings['page_set_password'], ['code' => $code, 'error' => $error]);
    }

    header('Location: '.$destination);
    exit;
}
