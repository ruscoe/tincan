<?php
/**
 * Template used to display a settings table row for page settings.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$setting = $data['setting'];
$pages = $data['pages'];
?>

<tr>
  <td><?php echo $setting->title; ?></td>
  <td>
    <select name="<?php echo $setting->setting_name; ?>">
      <?php
        foreach ($pages as $page) {
            $selected = ($page->page_id == $setting->value) ? ' selected' : '';
            echo "<option value=\"{$page->page_id}\"{$selected}>{$page->page_title}</option>\n";
        }
        ?>
    </select>
  </td>
</tr>
