<?php
  $setting = $data['setting'];
  $pages = $data['pages'];
?>

<tr>
  <td><?=$setting->title?></td>
  <td>
    <select name="<?=$setting->setting_name?>">
      <?php
        foreach ($pages as $page) {
            $selected = ($page->page_id == $setting->value) ? ' selected' : '';
            echo "<option value=\"{$page->page_id}\"{$selected}>{$page->page_title}</option>\n";
        }
      ?>
    </select>
  </td>
</tr>
