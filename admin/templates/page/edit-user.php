<?php

use TinCan\TCData;
use TinCan\TCUser;

/**
 * Page template for user editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCUser(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="username">Username</label>
  <input type="text" name="username" value="<?php echo $object->username; ?>" />

  <label for="email">Email Address</label>
  <input type="text" name="email" value="<?php echo $object->email; ?>" />

  <input type="hidden" name="object_type" value="user" />
  <input type="hidden" name="object_id" value="<?php echo $object->user_id; ?>" />
  <input type="submit" value="Update User" />
</form>
