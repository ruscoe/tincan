<?php
/**
 * Page template for post editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCPost(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="post_content">Post Content</label>
  <textarea name="content" rows="20" cols="30"><?php echo $object->content; ?></textarea>
  <input type="hidden" name="object_type" value="post" />
  <input type="hidden" name="object_id" value="<?php echo $object->post_id; ?>" />
  <input type="submit" value="Update Post" />
</form>
