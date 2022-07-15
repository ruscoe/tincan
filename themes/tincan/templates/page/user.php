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

$profile_user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

if (empty($profile_user_id)) {
  $profile_user_id = $slug;
}

$db = new TCData();

$profile_user = $db->load_user($profile_user_id);

if (empty($profile_user)) {
  header('Location: '.TCURL::create_url($settings['page_404']));
  exit;
}

$avatar = $profile_user->avatar;

$avatar_image = (!empty($avatar)) ? $profile_user->avatar : '/assets/images/default-profile.png';

TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $profile_user, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $profile_user->username; ?></h1>
  <div class="profile-image">
    <img src="<?php echo $avatar_image; ?>" />
    <?php
    if (!empty($user) && $user->can_edit_user($profile_user)) {
      if ($settings['enable_urls']) {
        $avatar_url = TCURL::create_friendly_url($settings['base_url_avatar'], $profile_user);
      } else {
        $avatar_url = TCURL::create_url($settings['page_user_avatar'], ['user' => $profile_user->user_id]);
      } ?>
      <div><a href="<?php echo $avatar_url; ?>">Change avatar</a></div>
    <?php
    } ?>
  </div>
  <div class="joined">Joined: <?php echo date($settings['date_format'], $profile_user->created_time); ?></div>
