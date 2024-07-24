<?php

use TinCan\db\TCData;
use TinCan\template\TCTemplate;
use TinCan\objects\TCBoard;
use TinCan\template\TCURL;

/**
 * Thread Deleted page template.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

$user = $data['user'];
$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

$board = $db->load_object(new TCBoard(), $board_id);

$board_url = (!empty($board)) ? TCURL::create_url($settings['page_board'], ['board' => $board->board_id]) : '/';

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Thread deleted. <a href="<?php echo $board_url; ?>">Return to board</a>.</p>
