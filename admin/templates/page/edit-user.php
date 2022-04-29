<?php

use TinCan\TCData;
use TinCan\TCUser;
use TinCan\TCRole;

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

$object = (!empty($object_id)) ? $db->load_user($object_id) : new TCUser();

// Get available user roles.
$roles = $db->load_objects(new TCRole());

$form_action = (!empty($object_id)) ? '/admin/actions/update-object.php' : '/admin/actions/create-user.php';
?>

<form id="edit-user" action="<?php echo $form_action; ?>" method="POST">
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

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input type="text" name="password" value="<?php echo (empty($object_id)) ? $object->generate_password() : '***'; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="role_id">Role</label>
    <div class="field">
      <select name="role_id">
        <?php
          foreach ($roles as $role) {
            $selected = ($role->role_id == $object->role_id) ? ' selected' : '';
            echo "<option value=\"{$role->role_id}\"{$selected}>{$role->role_name}</option>\n";
          }
        ?>
      </select>
    </div>
  </div>

  <input type="hidden" name="object_id" value="<?php echo $object->user_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($object_id)) ? 'Update User' : 'Add User'; ?>" />
  </div>
</form>
