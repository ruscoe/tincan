<?php

use TinCan\TCData;
use TinCan\TCPage;

/**
 * Page template for page editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Page' : 'Add New Page'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCPage(), $object_id) : new TCPage();
?>

<form id="edit-page" action="/admin/actions/update-object.php" method="POST">
  <div class="fieldset">
    <label for="page_title">Page Title</label>
    <div class="field">
      <input type="text" name="page_title" value="<?php echo $object->page_title; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="page_title">Page Template</label>
    <div class="field">
      <input type="text" name="template" value="<?php echo $object->template; ?>" />
    </div>
  </div>

  <input type="hidden" name="object_type" value="page" />
  <input type="hidden" name="object_id" value="<?php echo $object->page_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($object_id)) ? 'Update Page' : 'Add Page'; ?>" />
  </div>
</form>
