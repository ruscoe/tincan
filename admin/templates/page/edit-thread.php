<?php

use TinCan\TCData;
use TinCan\TCThread;

/**
 * Page template for thread editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Thread' : 'Add New Thread'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCThread(), $object_id) : new TCThread();
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="thread_title">Thread Title</label>
  <input type="text" name="thread_title" value="<?php echo $object->thread_title; ?>" />
  <input type="hidden" name="object_type" value="thread" />
  <input type="hidden" name="object_id" value="<?php echo $object->thread_id; ?>" />
  <input type="submit" value="Update Thread" />
</form>
