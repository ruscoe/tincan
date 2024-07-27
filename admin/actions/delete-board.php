<?php

use TinCan\controllers\TCBoardController;
use TinCan\template\TCURL;

/**
 * Tin Can board deletion handler.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_fate = filter_input(INPUT_POST, 'thread_fate', FILTER_SANITIZE_STRING);
$move_to_board_id = filter_input(INPUT_POST, 'move_to_board_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCBoardController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->delete_board($board_id, $thread_fate, $move_to_board_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the boards page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_boards'));
} else {
    // Send user back to the delete board page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_delete_board'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
