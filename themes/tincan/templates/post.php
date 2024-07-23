<?php

use TinCan\db\TCData;
use TinCan\content\TCPostParser;
use TinCan\template\TCURL;

/**
 * Post template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$thread = $data['thread'];
$page_number = $data['page_number'];
$post = $data['post'];
$author = $data['author'];
$user = $data['user'];
$settings = $data['settings'];

$avatar = null;
$user_page_url = null;
$username = null;

if (!empty($author)) {
    $username = $author->username;
    $avatar = $author->avatar;

    $user_page_url = TCURL::create_url($settings['page_user'], ['user' => $author->user_id]);
} else {
    $username = 'Deleted User';
}

$avatar_image = (!empty($avatar)) ? $avatar : '/assets/images/default-profile.png';

$post_url = TCURL::create_url($settings['page_thread'], ['thread' => $post->thread_id, 'start_at' => $page_number]);
$post_url .= '#post-'.$post->post_id;

$parser = new TCPostParser();
?>

<div id="post-<?php echo $post->post_id; ?>" class="post">
  <div class="post-user">
    <?php if (!empty($author)) { ?>
      <h3 class="username"><a href="<?php echo $user_page_url; ?>"><?php echo $username; ?></a></h3>
    <?php } else { ?>
      <h3 class="username"><?php echo $username; ?></h3>
    <?php } ?>
    <div class="profile-image">
      <?php if (!empty($author)) { ?>
        <a href="<?php echo $user_page_url; ?>"><img src="<?php echo $avatar_image; ?>" /></a>
      <?php } else { ?>
        <img src="<?php echo $avatar_image; ?>" />
      <?php } ?>
    </div>
    <?php if (!empty($author)) { ?>
      <div class="joined">Joined: <?php echo date($settings['date_format'], $author->created_time); ?></div>
    <?php } ?>
  </div>
  <div class="post-content">
    <div class="date">
      <a href="<?php echo $post_url; ?>"><?php echo date($settings['date_time_format'], $post->created_time); ?></a>
      <?php
        if ($post->updated_time != $post->created_time) {
            echo ' (edited '.date($settings['date_time_format'], $post->updated_time);

            if ($post->updated_by_user != $post->user_id) {
                $db = new TCData();
                $updated_by = $db->load_user($post->updated_by_user);
                echo ' by '.$updated_by->username;
            }

            echo ')';
        }
?>
    </div>
    <?php
$edit_post_url = TCURL::create_url($settings['page_edit_post'], ['post' => $post->post_id, 'page_number' => $page_number]);
$delete_post_url = TCURL::create_url($settings['page_delete_post'], ['post' => $post->post_id, 'page_number' => $page_number]);
?>
    <div class="content"><?php echo $parser->get_html($post->content); ?></div>
    <ul class="post-controls" data-post="<?php echo $post->post_id; ?>">
      <?php if (!empty($user) && $user->can_edit_post($post)) { ?>
        <li class="edit"><a href="<?php echo $edit_post_url; ?>">Edit</a></li>
      <?php } if (!empty($user) && $thread->post_can_be_deleted($post) && $user->can_delete_post($post)) { ?>
        <li class="delete"><a href="<?php echo $delete_post_url; ?>">Delete</a></li>
      <?php } ?>
    </ul>
  </div>
</div>
