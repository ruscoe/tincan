<?php

use TinCan\db\TCData;
use TinCan\template\TCTemplate;
use TinCan\objects\TCThread;
use TinCan\template\TCURL;

/**
 * User page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$page = $data['page'];
$user = $data['user'];

$profile_user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();

$profile_user = $db->load_user($profile_user_id);

if (empty($profile_user)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

$avatar = $profile_user->avatar;

$avatar_image = (!empty($avatar)) ? $profile_user->avatar : '/assets/images/default-profile.png';

$avatar_url = null;

if (!empty($user) && $user->can_edit_user($profile_user)) {
    $avatar_url = TCURL::create_url($settings['page_user_avatar'], ['user' => $profile_user->user_id]);
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $profile_user->get_name(), 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $profile_user->username; ?></h1>
<div id="user-profile">
  <div id="user-info">
    <div class="profile-image">
      <img src="<?php echo $avatar_image; ?>" />
      <?php if (!empty($avatar_url)) { ?>
        <div><a href="<?php echo $avatar_url; ?>">Change avatar</a></div>
      <?php } ?>
    </div>
    <div class="joined">Joined: <?php echo date($settings['date_format'], $profile_user->created_time); ?></div>
  </div>
  <div id="user-posts">
    <h2>Recent posts</h2>
    <?php
    $posts = $db->get_user_posts($profile_user->user_id, 0, 10);

if (!empty($posts)) {
    $thread = null; ?>
      <ul class="post-list">
          <?php
        foreach ($posts as $post) {
            $thread = $db->load_object(new TCThread(), $post->thread_id);

            $start_at = $db->get_post_page_in_thread($thread->thread_id, $post->post_id, $settings['posts_per_page']);

            $thread_url = TCURL::create_url($settings['page_thread'], ['thread' => $thread->thread_id, 'start_at' => $start_at]);

            $thread_url .= '#post-'.$post->post_id; ?>
        <li>
          <h3><a href="<?php echo $thread_url; ?>"><?php echo $thread->thread_title; ?></a></h3>
          <p class="post"><?php echo $post->get_trimmed_content(); ?></p>
          <p class="meta"><?php echo date($settings['date_time_format'], $post->created_time); ?></p>
        </li>
                <?php
        } ?>
      </ul>
          <?php
} else {
    ?>
      <p><?php echo $profile_user->username; ?> hasn't posted anything yet.</p>
        <?php
}
?>
  </div>
</div>
