<?php
/**
 * Post template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $post = $data['post'];
  $user = $data['user'];
  $settings = $data['settings'];

  $parser = new TCPostParser();
?>

<div id="post-<?php echo $post->post_id; ?>" class="post">
  <div class="post-user">
    <h3 class="username"><?php echo $user->username; ?></h3>
    <div class="profile-image">
      <img src="/assets/images/default-profile.png" />
    </div>
    <div class="joined">Joined: <?php echo date($settings['date_format'], $user->created_time); ?></div>
  </div>
  <div class="post-content">
    <div class="date"><?php echo date($settings['date_time_format'], $post->created_time); ?></div>
    <div class="content"><?php echo $parser->get_html($post->content); ?></div>
  </div>
</div>
