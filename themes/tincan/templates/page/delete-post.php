<?php

use TinCan\TCData;
use TinCan\TCPost;
use TinCan\TCTemplate;

/**
 * Delete Post page template.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);

$page = $data['page'];
$settings = $data['settings'];
$user = $data['user'];

$db = new TCData();

$post = $db->load_object(new TCPost(), $post_id);

if (empty($post)) {
  header('Location: '.TCURL::create_url($settings['page_404']));
  exit;
}

TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $post, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($user) && $user->can_delete_post($post)) {
  ?>

<div class="confirmation-box">

  <p>Really delete this post?</p>

  <form id="delete-post" action="/actions/delete-post.php" method="POST">
    <div class="fieldset button">
      <input type="hidden" name="post_id" value="<?php echo $post->post_id; ?>" />
      <input class="submit-button" type="submit" name="delete_post" value="Delete post" />
      <input class="submit-button" type="submit" name="cancel" value="Cancel" />
    </div>
  </form>

</div>

<?php
}
