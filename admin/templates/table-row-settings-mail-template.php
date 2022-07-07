<?php

use TinCan\TCData;
use TinCan\TCMailTemplate;

/**
 * Template used to display a settings table row for mail templates.
 *
 * @since 0.09
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $setting = $data['setting'];

  $db = new TCData();
  $templates = $db->load_objects(new TCMailTemplate());
?>

<tr>
  <td><?php echo $setting->title; ?></td>
  <td>
    <select name="<?php echo $setting->setting_name; ?>">
      <?php
        foreach ($templates as $template) {
          $selected = ($template->mail_template_id == $setting->value) ? ' selected' : '';
          ?>
          <option value="<?=$template->mail_template_id?>"<?=$selected?>><?=$template->mail_template_name?></option>
          <?php
        }
      ?>
    </select>
  </td>
</tr>
