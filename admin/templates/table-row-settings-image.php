<?php
/**
 * Template used to display a settings table row for image settings.
 *
 * @since 0.13
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $setting = $data['setting'];
?>

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field image-field">
    <img src="<?php echo $setting->value; ?>" width="128" />
    <span><a href="#">Edit image</a></span>
  </div>
</div>
