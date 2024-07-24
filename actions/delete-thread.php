<?php

use TinCan\db\TCData;
use TinCan\TCErrorMessage;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\objects\TCObject;
use TinCan\objects\TCThread;
use TinCan\template\TCURL;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can delete thread handler.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

try {
    $settings = $db->load_settings();
} catch (TCException $e) {
    echo $e->getMessage();
    exit;
}

$thread = $db->load_object(new TCThread(), $thread_id);

if (empty($thread)) {
    $error = TCObject::ERR_NOT_FOUND;
}

if (empty($error) && isset($_POST['cancel'])) {
    // Cancel thread deletion and return user to the thread.
    $destination = TCURL::create_url($settings['page_thread'], ['thread' => $thread->thread_id]);

    header('Location: '.$destination);
    exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check user has permission to delete this thread.
if (empty($error) && (empty($user) || !$user->can_delete_thread($thread))) {
    $error = TCUser::ERR_NOT_AUTHORIZED;
}

$thread->deleted = true;

try {
    $db->save_object($thread);
} catch (TCException $e) {
    $error = TCObject::ERR_NOT_SAVED;
}

$destination = '';

if (empty($error)) {
    // Send user to the confirmation page.
    $destination = TCURL::create_url(
        $settings['page_thread_deleted'],
        [
        'board' => $thread->board_id,
        ]
    );
} else {
    // Send user back to the delete thread page with an error.
    $destination = TCURL::create_url(
        $settings['page_delete_thread'],
        [
        'thread' => $thread->thread_id,
        'error' => $error,
        ]
    );
}

header('Location: '.$destination);
exit;
