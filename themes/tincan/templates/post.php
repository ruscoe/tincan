<?php

use TinCan\TCPostParser;

  /**
   * Post template.
   *
   * @since 0.01
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $post = $data['post'];
  $author = $data['author'];
  $user = $data['user'];
  $settings = $data['settings'];

  $parser = new TCPostParser();

  $user_page_url = "/?page={$settings['page_user']}&user={$author->user_id}";
?>

<div id="post-<?php echo $post->post_id; ?>" class="post">
  <div class="post-user">
    <h3 class="username"><a href="<?php echo $user_page_url; ?>"><?php echo $author->username; ?></a></h3>
    <div class="profile-image">
      <a href="<?php echo $user_page_url; ?>"><img src="/assets/images/default-profile.png" /></a>
    </div>
    <div class="joined">Joined: <?php echo date($settings['date_format'], $author->created_time); ?></div>
  </div>
  <div class="post-content">
    <div class="date"><?php echo date($settings['date_time_format'], $post->created_time); ?></div>
    <div class="content"><?php echo $parser->get_html($post->content); ?></div>
    <ul class="post-controls">
      <?php if (!empty($user) && $user->can_edit_post($post)) { ?>
        <li><a href="/?page=<?php echo $settings['page_edit_post']; ?>&post=<?php echo $post->post_id; ?>">Edit</a></li>
      <?php } if (!empty($user) && $user->can_delete_post($post)) { ?>
        <li><a href="/?page=<?php echo $settings['page_delete_post']; ?>&post=<?php echo $post->post_id; ?>">Delete</a></li>
      <?php } ?>
    </ul>
  </div>
</div>