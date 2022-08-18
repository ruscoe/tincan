<?php

use TinCan\TCData;
use TinCan\TCUser;

/**
 * Page template for user deletion.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];

$delete_user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$delete_user = $db->load_object(new TCUser(), $delete_user_id);

if ((!empty($delete_user)) && ($delete_user->user_id == $user->user_id)) {
  ?>
  <h1>Can't delete own account</h1>
  <p>You can't delete the account you're logged in to.</p>
<?php
} elseif (!empty($delete_user)) {
    ?>
    <h1>Really delete <?php echo $delete_user->get_name(); ?>?</h1>

    <form id="delete-object" action="/admin/actions/delete-user.php" method="POST">
      <input type="hidden" name="user_id" value="<?php echo $delete_user->user_id; ?>" />

      <div class="fieldset button">
        <input class="submit-button" type="submit" value="Delete User" />
      </div>
    </form>
<?php
  } else {
    ?>
  <h1>User not found</h1>
  <p>This user either never existed or has already been deleted.</p>
  <?php
  }
?>
