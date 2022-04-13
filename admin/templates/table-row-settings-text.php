<?php
/**
 * Template used to display a settings table row for text settings.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

  $setting = $data['setting'];
?>

<tr>
  <td><?=$setting->title?></td>
  <td><input type="text" name="<?=$setting->setting_name?>" value="<?=$setting->value?>" /></td>
</tr>
