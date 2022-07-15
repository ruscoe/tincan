<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCSetting;

/**
 * Page template for forum settings.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

$db = new TCData();

$settings = $db->load_objects(new TCSetting());

$pages = $db->load_objects(new TCPage());
?>

<form id="forum-settings" action="/admin/actions/save-settings.php" method="POST">

  <?php
  $settings_by_cat = [];
  foreach ($settings as $setting) {
    if (!isset($settings_by_cat[$setting->category])) {
      $settings_by_cat[$setting->category] = [];
    }

    $settings_by_cat[$setting->category][] = $setting;
  }

  $last_category = null;

  foreach ($settings_by_cat as $category => $settings) {
    ?>
    <div class="setting-category">
      <h2><?php echo $settings[0]->category; ?></h2>
      <table>
    <?php
    foreach ($settings as $setting) {
      switch ($setting->type) {
      case 'page':
        // Don't display page settings. Too easy to break the entire forum by
        // reassigning default pages.
        // TCAdminTemplate::render('table-row-settings-page', ['setting' => $setting, 'pages' => $pages]);
      break;
      case 'bool':
        TCAdminTemplate::render('table-row-settings-bool', ['setting' => $setting]);
      break;
      case 'role':
        TCAdminTemplate::render('table-row-settings-user-role', ['setting' => $setting]);
      break;
      case 'mail_template':
        TCAdminTemplate::render('table-row-settings-mail-template', ['setting' => $setting]);
      break;
      default:
        TCAdminTemplate::render('table-row-settings-text', ['setting' => $setting]);
    }
    } ?>
    </table>
  </div>
  <?php
  }
  ?>
  </table>

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Save settings" />
  </div>
</form>
