<?php

use TinCan\db\TCData;
use TinCan\objects\TCObject;
use TinCan\objects\TCUser;
use TinCan\template\TCTemplate;
use TinCan\template\TCURL;

/**
 * Edit Account page template.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$page = $data['page'];
$settings = $data['settings'];
$user = $data['user'];

$db = new TCData();

$edit_user = $db->load_object(new TCUser(), $user_id);

if (empty($edit_user)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

// Check user has permission to edit this user.
if (empty($user) || !$user->can_edit_user($edit_user)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $edit_user, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php

if (!empty($error)) {

    switch ($error) {
        case TCUser::ERR_NOT_AUTHORIZED:
            $error_msg = 'You cannot edit this account.';
            break;
        case TCUser::ERR_EMAIL:
            $error_msg = 'Please check your email address.';
            break;
        case TCUser::ERR_PASSWORD:
            $error_msg = 'Please check your current password.';
            break;
        case TCObject::ERR_NOT_FOUND:
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Account could not be edited at this time. Please try again later.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error_msg], 'page' => $page]);
}
?>

<form id="update-post" action="/actions/update-user.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input name="username" value="<?php echo $edit_user->username; ?>" disabled />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email</label>
    <div class="field">
      <input name="email" value="<?php echo $edit_user->email; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="current_pass">Current Password</label>
    <div class="field">
      <input type="password" name="current_pass" value="" />
    </div>
  </div>

  <div class="fieldset">
    <label for="new_pass">New Password</label>
    <div class="field">
      <input type="password" name="new_pass" value="" />
    </div>
  </div>

  <input type="hidden" name="user" value="<?php echo $edit_user->user_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="submit_account" value="Save changes" />
  </div>
</form>
