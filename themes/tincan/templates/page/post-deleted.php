<?php

use TinCan\db\TCData;
use TinCan\template\TCTemplate;
use TinCan\objects\TCThread;
use TinCan\template\TCURL;

/**
 * Post Deleted page template.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);

$user = $data['user'];
$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

$url_id = ($settings['enable_urls']) ? $settings['base_url_threads'] : $settings['page_thread'];
$thread_url = (!empty($thread)) ? TCURL::create_url($url_id, ['thread' => $thread->thread_id], $settings['enable_urls'], $thread->get_slug()) : '/';

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Post deleted. <a href="<?php echo $thread_url; ?>">Return to thread</a>.</p>
