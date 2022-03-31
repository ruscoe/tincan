<?php

$page = $data['page'];
?>

<h1><?=$page->page_title?></h1>

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
        TCAdminTemplate::render('table-row-settings-page', array('setting' => $setting, 'pages' => $pages));
      break;
      default:
        TCAdminTemplate::render('table-row-settings-text', array('setting' => $setting));
    }
  }
  ?>
  </table>

  <input type="submit" value="Save settings" />
</form>
