<?php

use TinCan\TCBoard;
use TinCan\TCData;

/**
 * Page template for admin board editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($object_id)) ? 'Edit Board' : 'Add New Board'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCBoard(), $object_id) : new TCBoard();
?>

<form id="edit-board" action="/admin/actions/update-object.php" method="POST">
  <div class="fieldset">
    <label for="board_name">Board Name</label>
    <div class="field">
      <input type="text" name="board_name" value="<?php echo $object->board_name; ?>" />
    </div>
  </div>

  <input type="hidden" name="object_type" value="board" />
  <input type="hidden" name="object_id" value="<?php echo $object->board_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="Update Board" />
  </div>
</form>
