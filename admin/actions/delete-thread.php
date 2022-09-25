<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCPost;
use TinCan\TCThread;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can thread deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$thread_id = filter_input(INPUT_POST, 'thread_id', FILTER_SANITIZE_NUMBER_INT);

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

if (empty($thread)) {
    throw new TCException('Unable to find thread ID '.$thread_id);
}

$db->delete_object(new TCThread(), $thread->thread_id);

$posts = $db->load_objects(new TCPost(), null, [['field' => 'thread_id', 'value' => $thread->thread_id]]);

foreach ($posts as $post) {
    $db->delete_object(new TCPost(), $post->post_id);
}

$destination = '/admin/index.php?page='.$settings['admin_page_threads'];

header('Location: '.$destination);
exit;
