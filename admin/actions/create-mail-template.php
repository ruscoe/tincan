<?php

use TinCan\db\TCData;
use TinCan\objects\TCMailTemplate;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can mail template creation handler.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/vendor/autoload.php';

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

$error = null;

$mail_template = new TCMailTemplate();

$mail_template->mail_template_name = $mail_template_name;
$mail_template->content = $content;
$mail_template->created_time = time();
$mail_template->updated_time = time();

$saved_mail_template = $db->save_object($mail_template);

// Verify mail template has been created.
if (empty($saved_mail_template)) {
    $error = TCObject::ERR_NOT_SAVED;
}

// Return to the mail templates page.
$destination = '/admin/index.php?page='.$settings['admin_page_mail_templates'].'&error='.$error;
header('Location: '.$destination);
exit;
