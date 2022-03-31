<?php
  $setting = $data['setting'];
?>

<tr>
  <td><?=$setting->title?></td>
  <td><input type="text" name="<?=$setting->setting_name?>" value="<?=$setting->value?>" /></td>
</tr>
