<?php

use TinCan\db\TCData;

/**
 * Page template for uploading an image via forum settings.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$setting = filter_input(INPUT_GET, 'setting', FILTER_SANITIZE_STRING);
?>

<h1>Upload Image</h1>

<?php
$db = new TCData();
$settings = $db->load_settings();

// Avoid browser cache so latest image always appears.
$image_path = $settings[$setting].'?v='.time();
?>

<img src="<?php echo $image_path; ?>" width="128" />

<form id="upload-image" action="/admin/actions/upload-setting-image.php" method="POST" enctype="multipart/form-data">

<div class="fieldset">
  <label for="image_file">Image file</label>
  <div class="field">
    <input type="file" name="image_file">
  </div>
</div>

<input type="hidden" name="setting" value="<?php echo $setting; ?>" />

<div class="fieldset button">
  <input class="submit-button" type="submit" name="image" value="Upload image" />
</div>

</form>
