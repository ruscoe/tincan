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

<div class="fieldset">
  <label for="<?php echo $setting->setting_name; ?>"><?php echo $setting->title; ?></label>
  <div class="field">
    <select name="<?php echo $setting->setting_name; ?>">
      <?php
        foreach ($templates as $template) {
          $selected = ($template->mail_template_id == $setting->value) ? ' selected' : ''; ?>
          <option value="<?php echo $template->mail_template_id; ?>"<?php echo $selected; ?>><?php echo $template->mail_template_name; ?></option>
          <?php
        }
      ?>
    </select>
  </div>
</div>
