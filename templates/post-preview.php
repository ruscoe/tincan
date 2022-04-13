<?php
/**
 * Post preview template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $post = $data['post'];
?>

<div class="post-preview">
  <h2><a href="<?=$post->url?>"><?=$post->title?></a></h2>
</div>
