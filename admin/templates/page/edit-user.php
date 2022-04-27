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

<h1><?php echo (!empty($object_id)) ? 'Edit User' : 'Add New User'; ?></h1>

<?php

$db = new TCData();

$object = (!empty($object_id)) ? $db->load_object(new TCUser(), $object_id) : new TCUser();
?>

<form id="edit-user" action="/admin/actions/update-object.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input type="text" name="username" value="<?php echo $object->username; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email Address</label>
    <div class="field">
      <input type="text" name="email" value="<?php echo $object->email; ?>" />
    </div>
  </div>

  <input type="hidden" name="object_type" value="user" />
  <input type="hidden" name="object_id" value="<?php echo $object->user_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="Update User" />
  </div>
</form>
