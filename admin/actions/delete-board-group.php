<?php

use TinCan\controllers\TCBoardGroupController;
use TinCan\template\TCURL;

/**
 * Tin Can board group deletion handler.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$board_group_id = filter_input(INPUT_POST, 'board_group_id', FILTER_SANITIZE_NUMBER_INT);
$board_fate = filter_input(INPUT_POST, 'board_fate', FILTER_SANITIZE_STRING);
$move_to_board_group_id = filter_input(INPUT_POST, 'move_to_board_group_id', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCBoardGroupController();

$controller->authenticate_user();

if (!$controller->is_admin_user()) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$controller->get_setting('page_log_in'));
    exit;
}

$controller->delete_board_group($board_group_id, $board_fate, $move_to_board_group_id);

$destination = '';

if (empty($controller->get_error())) {
    // Send user to the board groups page.
    $destination = TCURL::create_admin_url($controller->get_setting('admin_page_board_groups'));
} else {
    // Send user back to the delete board group page with an error.
    $destination = TCURL::create_admin_url(
        $controller->get_setting('admin_page_delete_board_group'),
        [
        'error' => $controller->get_error(),
        ]
    );
}

header('Location: '.$destination);
exit;
