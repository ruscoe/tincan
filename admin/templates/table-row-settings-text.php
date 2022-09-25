<?php
/**
 * Template used to display a settings table row for text settings.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$setting = $data['setting'];
?>

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field">
    <input type="text" name="<?php echo $setting->setting_name; ?>" value="<?php echo $setting->value; ?>" />
  </div>
</div>
