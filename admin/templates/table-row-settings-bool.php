<?php
/**
 * Template used to display a settings table row for boolean settings.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $setting = $data['setting'];
  $state = ('true' == $setting->value);
?>

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field">
    <input type="checkbox" name="<?php echo $setting->setting_name; ?>"<?php echo ($state) ? ' checked' : ''; ?>>
  </div>
</div>
