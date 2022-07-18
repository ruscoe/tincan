<?php

/**
 * Post preview template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$post = $data['post'];
?>

<div class="post-preview">
  <h2 class="section-subheader"><a href="<?php echo $post->url; ?>"><?php echo $post->title; ?></a></h2>
</div>
