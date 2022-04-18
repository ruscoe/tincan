<?php
/**
 * Edit Post page template.
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

TCTemplate::render('breadcrumbs', ['object' => $post, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($user) && $user->can_edit_post($post)) {
?>

<form id="update-post" action="/actions/update-post.php" method="POST">
  <div class="fieldset">
    <label for="thread_title">Reply Content</label>
    <textarea name="post_content" rows="20" cols="30"><?php echo $post->content; ?></textarea>
  </div>

  <input type="hidden" name="post_id" value="<?php echo $post->post_id; ?>" />
  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="submit_post" value="Save changes" />
  </div>
</form>

<?php
}
