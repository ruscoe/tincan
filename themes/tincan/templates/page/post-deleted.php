<?php

use TinCan\TCData;
use TinCan\TCThread;
use TinCan\TCURL;

/**
 * Post Deleted page template.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread_id = filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

if ($settings['enable_urls']) {
  $thread_url = TCURL::create_friendly_url($settings['base_url_threads'], $thread);
} else {
  $thread_url = TCURL::create_url($settings['page_thread'], ['thread' => $thread_id]);
}
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Post deleted. <a href="<?php echo $thread_url; ?>">Return to thread</a>.</p>
