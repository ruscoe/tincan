<?php

use TinCan\TCMailer;
use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can email test handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$recipient = filter_input(INPUT_POST, 'recipient', FILTER_SANITIZE_STRING);

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

$error = '';

$mailer = new TCMailer($settings);

try {
    $mailer->send_mail(
        $settings['site_email_name'],
        $settings['site_email_address'],
        'Test Email From Tin Can Forum',
        'This is a test.',
        [['name' => $recipient, 'email' => $recipient]],
    );
} catch (Exception $e) {
    $error = TCMailer::ERR_SMTP;
}

$destination = '/admin/index.php?page='.$settings['admin_page_test_mail'].'&error='.$error;

header('Location: '.$destination);
exit;
