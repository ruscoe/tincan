<?php
/**
 * Template used to display a settings table row for boolean settings.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $setting = $data['setting'];
  $state = ($setting->value == 'true');
?>

<tr>
  <td><?=$setting->title?></td>
  <td><input type="checkbox" name="<?=$setting->setting_name?>"<?php echo ($state) ? ' checked' : '' ?>></td>
</tr>
