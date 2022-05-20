<?php

use TinCan\TCData;
use TinCan\TCTemplate;
use TinCan\TCURL;

/**
 * User page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$slug = $data['slug'];
$page = $data['page'];
$user = $data['user'];

$user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

if (empty($user_id)) {
  $user_id = $slug;
}

$db = new TCData();

$profile_user = $db->load_user($user_id);

if (empty($profile_user)) {
  header('Location: '.TCURL::create_url($settings['page_404']));
  exit;
}

$avatar = $profile_user->avatar;

// TODO: Error handling for missing user (404).

$avatar_image = (!empty($avatar)) ? '/uploads/avatars/'.$profile_user->avatar : '/assets/images/default-profile.png';

TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $profile_user, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $profile_user->username; ?></h1>
  <div class="profile-image">
    <img src="<?php echo $avatar_image; ?>" />
    <?php if (!empty($user) && $user->can_edit_user($profile_user)) { ?>
      <div><a href="<?php echo TCURL::create_url($settings['page_user_avatar']); ?>">Change avatar</a></div>
    <?php } ?>
  </div>
  <div class="joined">Joined: <?php echo date($settings['date_format'], $profile_user->created_time); ?></div>
