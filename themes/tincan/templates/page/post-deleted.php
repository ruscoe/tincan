<?php
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
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Post deleted. <a href="<?php echo TCURL::create_url($settings['page_thread'], ['thread' => $thread_id]); ?>">Return to thread</a>.</p>
