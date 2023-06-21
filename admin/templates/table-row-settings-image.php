<?php

use TinCan\db\TCData;

/**
 * Template used to display a settings table row for image settings.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$setting = $data['setting'];

$db = new TCData();
$settings = $db->load_settings();

$upload_url = '/admin/index.php?page='.$settings['admin_page_upload_setting_image'].'&setting='.$setting->setting_name;
$delete_url = '/admin/index.php?page='.$settings['admin_page_delete_setting_image'].'&setting='.$setting->setting_name;
?>

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field image-field">
    <img src="<?php echo $setting->value; ?>" width="128" />
    <span><a href="<?php echo $upload_url; ?>">Upload image</a></span>
    <span><a href="<?php echo $delete_url; ?>">Delete image</a></span>
  </div>
</div>
