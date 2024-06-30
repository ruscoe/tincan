<?php

use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can thread update handler.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = trim(filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING));
$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);

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

$thread = $db->load_object(new TCThread(), $thread_id);

$error = null;

if (empty($thread)) {
    $error = TCObject::ERR_NOT_FOUND;
}

$saved_thread = null;

if (empty($error)) {
    $thread->thread_title = $thread_title;
    $thread->board_id = $board_id;
    $thread->updated_time = time();

    $saved_thread = $db->save_object($thread);

    // Verify thread has been updated.
    if (empty($saved_thread)) {
        $error = TCObject::ERR_NOT_SAVED;
    }
}

// Return to the boards page.
$destination = '/admin/index.php?page='.$settings['admin_page_threads'].'&error='.$error;
header('Location: '.$destination);
exit;
