<?php

use TinCan\TCData;
use TinCan\TCTemplate;

  /**
   * User page template.
   *
   * @since 0.01
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $page = $data['page'];
  $settings = $data['settings'];

  $user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

  $db = new TCData();

  $user = $db->load_user($user_id);

  // TODO: Error handling for missing user (404).

  TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $user, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $user->username; ?></h1>
  <div class="profile-image">
    <a href="<?php echo $user_page_url; ?>"><img src="/assets/images/default-profile.png" /></a>
  </div>
  <div class="joined">Joined: <?php echo date($settings['date_format'], $author->created_time); ?></div>
