<?php

use TinCan\TCTemplate;

  /**
   * User avatar page template.
   *
   * @since 0.05
   *
   * @author Dan Ruscoe danruscoe@protonmail.com
   */
  $page = $data['page'];

  $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($error)) {
    TCTemplate::render('form-errors', $settings['theme'], ['errors' => [$error], 'page' => $page]);
  }
?>

<form id="upload-avatar" action="/actions/upload-avatar.php" method="POST" enctype="multipart/form-data">

<div class="fieldset">
  <label for="username">Username</label>
  <div class="field">
    <input type="file" name="avatar_image">
  </div>
</div>

  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="avatar" value="Upload new avatar" />
  </div>
</form>
