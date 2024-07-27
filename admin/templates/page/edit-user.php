<?php

use TinCan\db\TCData;
use TinCan\objects\TCRole;
use TinCan\objects\TCUser;

/**
 * Page template for user editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];

$update_user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($update_user_id)) ? 'Edit User' : 'Add New User'; ?></h1>

<?php

$db = new TCData();

$update_user = (!empty($update_user_id)) ? $db->load_user($update_user_id) : new TCUser();

// Get available user roles.
$roles = $db->load_objects(new TCRole());
?>

<form id="edit-user" action="/admin/actions/update-user.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input type="text" name="username" value="<?php echo $update_user->username; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email Address</label>
    <div class="field">
      <input type="text" name="email" value="<?php echo $update_user->email; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input type="password" name="password" value="<?php echo (empty($update_user_id)) ? $update_user->generate_password() : '***'; ?>" />
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

  <?php
    // Users cannot suspend themselves.
    $can_suspend = ($user->user_id !== $update_user->user_id);
?>

  <div class="fieldset">
    <label for="suspended">Suspended</label>
    <div class="field">
      <input type="checkbox" name="suspended"<?php echo ($update_user->suspended) ? ' checked' : ''; ?><?php echo (!$can_suspend) ? ' disabled' : ''; ?>>
    </div>
  </div>

  <input type="hidden" name="user_id" value="<?php echo $update_user->user_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($update_user_id)) ? 'Update User' : 'Add User'; ?>" />
  </div>
</form>
