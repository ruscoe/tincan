<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCData;
use TinCan\TCSetting;
use TinCan\TCPage;

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

<form action="/admin/actions/save-settings.php" method="POST">
  <table>
  <?php

  foreach ($settings as $setting) {
    switch ($setting->type) {
      case 'page':
        TCAdminTemplate::render('table-row-settings-page', ['setting' => $setting, 'pages' => $pages]);
      break;
      case 'bool':
        TCAdminTemplate::render('table-row-settings-bool', ['setting' => $setting]);
      break;
      default:
        TCAdminTemplate::render('table-row-settings-text', ['setting' => $setting]);
    }
  }
  ?>
  </table>

  <input type="submit" value="Save settings" />
</form>
