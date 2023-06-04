<?php

use TinCan\db\TCData;
use TinCan\objects\TCMailTemplate;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can mail template update handler.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';


$mail_template_id = filter_input(INPUT_POST, 'mail_template_id', FILTER_SANITIZE_NUMBER_INT);
$mail_template_name = filter_input(INPUT_POST, 'mail_template_name', FILTER_SANITIZE_STRING);
$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

$db = new TCData();
$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$settings['page_log_in']);
    exit;
}

$mail_template = $db->load_object(new TCMailTemplate(), $mail_template_id);

$error = null;

if (empty($mail_template)) {
    $error = TCObject::ERR_NOT_FOUND;
}

$saved_mail_template = null;

if (empty($error)) {
    $mail_template->mail_template_name = $mail_template_name;
    $mail_template->content = $content;

    $saved_mail_template = $db->save_object($mail_template);

    // Verify mail template has been updated.
    if (empty($saved_mail_template)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the mail templates page.
$destination = '/admin/index.php?page='.$settings['admin_page_mail_templates'].'&error='.$error;
header('Location: '.$destination);
exit;
