<?php

use TinCan\TCData;
use TinCan\TCRole;
use TinCan\TCUser;

/**
 * Page template for user editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($user_id)) ? 'Edit User' : 'Add New User'; ?></h1>

<?php

$db = new TCData();

$user = (!empty($user_id)) ? $db->load_user($user_id) : new TCUser();

// Get available user roles.
$roles = $db->load_objects(new TCRole());

$form_action = (!empty($user_id)) ? '/admin/actions/update-user.php' : '/admin/actions/create-user.php';
?>

<form id="edit-user" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input type="text" name="username" value="<?php echo $user->username; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email Address</label>
    <div class="field">
      <input type="text" name="email" value="<?php echo $user->email; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input type="text" name="password" value="<?php echo (empty($user_id)) ? $user->generate_password() : '***'; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="role_id">Role</label>
    <div class="field">
      <select name="role_id">
        <?php
          foreach ($roles as $role) {
            $selected = ($role->role_id == $user->role_id) ? ' selected' : '';
            echo "<option value=\"{$role->role_id}\"{$selected}>{$role->role_name}</option>\n";
          }
        ?>
      </select>
    </div>
  </div>

  <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($user_id)) ? 'Update User' : 'Add User'; ?>" />
  </div>
</form>
