<?php

use TinCan\TCData;
use TinCan\TCRole;

/**
 * Template used to display a settings table row for user roles.
 *
 * @since 0.09
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $setting = $data['setting'];

  $db = new TCData();
  $roles = $db->load_objects(new TCRole());
?>

<tr>
  <td><?php echo $setting->title; ?></td>
  <td>
    <select name="<?php echo $setting->setting_name; ?>">
      <?php
        foreach ($roles as $role) {
          $selected = ($role->role_id == $setting->value) ? ' selected' : '';
          ?>
          <option value="<?=$role->role_id?>"<?=$selected?>><?=$role->role_name?></option>
          <?php
        }
      ?>
    </select>
  </td>
</tr>
