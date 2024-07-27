<?php

use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\objects\TCObject;
use TinCan\template\TCTemplate;

/**
 * Page template for user deletion.
 *
 * @since 0.12
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

$user = $db->load_object(new TCUser(), $user_id);

if (empty($error) && empty($user)) {
    $error = TCObject::ERR_NOT_FOUND;
}

// Error handling.
if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'User not found.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'User could not be updated.';
            break;
        case TCUser::ERR_NOT_AUTHORIZED:
            $error_msg = 'You are not authorized to delete this user.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

if (!empty($user)) {
    ?>
    <h1>Really delete <?php echo $user->get_name(); ?>?</h1>

    <form id="delete-object" action="/admin/actions/delete-user.php" method="POST">
      <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />

      <div class="fieldset button">
        <input class="submit-button" type="submit" value="Delete User" />
      </div>
    </form>
    <?php
}
