<?php

use TinCan\TCData;
use TinCan\TCTemplate;
use TinCan\TCURL;
use TinCan\TCUser;

/**
 * User avatar page template.
 *
 * @since 0.05
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$slug = $data['slug'];
$page = $data['page'];
$user = $data['user'];

$avatar_user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

if (empty($avatar_user_id)) {
    $avatar_user_id = $slug;
}

$avatar_user = $db->load_object(new TCUser(), $avatar_user_id);

if (empty($avatar_user)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

// Check user has permission to edit this user's avatar.
if (empty($user) || !$user->can_edit_user($avatar_user)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
}

  $avatar_image = (!empty($avatar_user->avatar)) ? $avatar_user->avatar : '/assets/images/default-profile.png';

// Avoid browser cache so latest image always appears.
$avatar_image .= '?v='.time();
?>

<img src="<?php echo $avatar_image; ?>" width="128" />

<form id="upload-avatar" action="/actions/upload-avatar.php" method="POST" enctype="multipart/form-data">

  <div class="fieldset">
    <label for="avatar_image">Image file</label>
    <div class="field">
      <input type="file" name="avatar_image">
    </div>
  </div>

  <input type="hidden" name="user_id" value="<?php echo $avatar_user->user_id; ?>" />
  <input class="ajax" type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" name="avatar" value="Upload new avatar" />
  </div>

</form>
