<?php

use TinCan\TCData;
use TinCan\TCSetting;

/**
 * Page template for deleting an image via forum settings.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$setting = filter_input(INPUT_GET, 'setting', FILTER_SANITIZE_STRING);
?>

<h1>Delete Image</h1>

<?php
$db = new TCData();

// Check the given setting exists and is an image type setting.
$setting_objects = $db->get_indexed_objects(new TCSetting(), 'setting_name');
$image_setting = $setting_objects[$setting];

if (!empty($image_setting) && ($image_setting->type == 'image')) {
    $settings = $db->load_settings();

    // Avoid browser cache so latest image always appears.
    $image_path = $settings[$setting].'?v='.time();
    ?>

<h1>Really delete <?php echo $image_setting->setting_name; ?>?</h1>

<img src="<?php echo $image_path; ?>" width="128" />

<form id="delete-object" action="/admin/actions/delete-setting-image.php" method="POST">
  <input type="hidden" name="setting" value="<?php echo $setting; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete" />
  </div>
</form>

    <?php
} else {
    ?>
  <h1>Image setting not found</h1>
  <p>This image setting does not exist.</p>
    <?php
}
?>
