<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\TCMailTemplate;
use TinCan\TCData;

/**
 * Page template for mail templates list.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
$settings = $data['settings'];
?>

<h1><?php echo $page->page_title; ?></h1>

<div class="objects-nav">
  <a class="admin-button" href="/admin/index.php?page=<?php echo $settings['admin_page_edit_mail_template']; ?>">New Mail Template</a>
</div>

<?php

$db = new TCData();

// TODO: Sorting and pagination.
$conditions = [];
$order = [];

$mail_templates = $db->load_objects(new TCMailTemplate(), [], $conditions, $order);
?>

<table class="objects">
  <th>Template Name</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($mail_templates as $template) {
  $data = [
    'title' => $template->mail_template_name,
    'object_id' => $template->mail_template_id,
    // TODO: Preview URL.
    'view_url' => '',
    'edit_page_id' => $settings['admin_page_edit_mail_template'],
    'edit_url' => '/admin/index.php?page='.$settings['admin_page_edit_mail_template'].'&mail_template_id='.$template->mail_template_id,
    'delete_url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=mail_template&object_id='.$template->mail_template_id,
  ];
  TCAdminTemplate::render('table-row', $data);
}
?>
</table>
