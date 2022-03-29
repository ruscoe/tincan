<?php

require '../tc-config.php';

require '../includes/include-db.php';
require '../includes/include-objects.php';
require '../includes/include-template.php';

$board_id = filter_input(INPUT_POST, 'board_id', FILTER_SANITIZE_NUMBER_INT);
$thread_title = filter_input(INPUT_POST, 'thread_title', FILTER_SANITIZE_STRING);
$thread_post = filter_input(INPUT_POST, 'thread_post', FILTER_SANITIZE_STRING);

var_dump($board_id);
var_dump($thread_title);

// TODO: Validate user.
// TODO: Validate board ID.
// TODO: Validate post content.

$thread = new TCThread();
$thread->board_id = $board_id;
$thread->thread_title = $thread_title;

$db = new TCData();

$new_thread = $db->save_object($thread);

var_dump($new_thread);
