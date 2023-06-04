<?php

use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\objects\TCObject;
use TinCan\objects\TCPendingUser;
use TinCan\template\TCTemplate;
use TinCan\user\TCUserSession;

/**
 * Tin Can confirm account handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';


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
} else {
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
}

// Render page.
$page_template = 'confirm-account';

TCTemplate::render('page/'.$page_template, $settings['theme'], ['settings' => $settings, 'user' => $user, 'error' => $error]);
TCTemplate::render('footer', $settings['theme'], null);
