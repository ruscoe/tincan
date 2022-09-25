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

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field">
    <select name="<?php echo $setting->setting_name; ?>">
      <?php
        foreach ($roles as $role) {
            $selected = ($role->role_id == $setting->value) ? ' selected' : ''; ?>
          <option value="<?php echo $role->role_id; ?>"<?php echo $selected; ?>><?php echo $role->role_name; ?></option>
          <?php
        }
?>
    </select>
  </div>
</div>
