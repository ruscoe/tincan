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

<tr>
  <td><?php echo $setting->title; ?></td>
  <td><input type="text" name="<?php echo $setting->setting_name; ?>" value="<?php echo $setting->value; ?>" /></td>
</tr>
