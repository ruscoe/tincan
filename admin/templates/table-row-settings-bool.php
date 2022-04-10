<?php
  $setting = $data['setting'];
  $state = ($setting->value == 'true');
?>

<tr>
  <td><?=$setting->title?></td>
  <td><input type="checkbox" name="<?=$setting->setting_name?>"<?php echo ($state) ? ' checked' : '' ?>></td>
</tr>
