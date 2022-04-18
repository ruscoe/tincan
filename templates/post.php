<?php
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
?>

<div id="post-<?php echo $post->post_id; ?>" class="post">
  <div class="post-user">
    <h3 class="username"><?php echo $author->username; ?></h3>
    <div class="profile-image">
      <img src="/assets/images/default-profile.png" />
    </div>
    <div class="joined">Joined: <?php echo date($settings['date_format'], $author->created_time); ?></div>
  </div>
  <div class="post-content">
    <div class="date"><?php echo date($settings['date_time_format'], $post->created_time); ?></div>
    <div class="content"><?php echo $parser->get_html($post->content); ?></div>
    <ul class="post-controls">
      <?php if (!empty($user) && $user->can_edit_post($post)) { ?>
        <li><a href="/?page=<?=$settings['page_edit_post']?>&post=<?=$post->post_id?>">Edit</a></li>
      <?php } if (!empty($user) && $user->can_delete_post($post)) { ?>
        <li><a href="/?page=<?=$settings['page_delete_post']?>&post=<?=$post->post_id?>">Delete</a></li>
      <?php } ?>
    </ul>
  </div>
</div>
