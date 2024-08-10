<?php

use TinCan\controllers\TCPostController;
use TinCan\template\TCURL;

/**
 * Tin Can delete attachment handler.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
$attachment_id = filter_input(INPUT_GET, 'attachment', FILTER_SANITIZE_NUMBER_INT);

$controller = new TCPostController();

$controller->authenticate_user();

if ($controller->can_delete_attachment($attachment_id)) {
    $controller->delete_attachment($attachment_id);
}

$destination = '';

// Send user to the edit post page.
$destination = TCURL::create_url(
    $controller->get_setting('page_edit_post'),
    [
    'post' => $post_id,
    ]
) . '#attachments';

header('Location: '.$destination);
exit;
