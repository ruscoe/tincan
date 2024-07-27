<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\db\TCData;
use TinCan\objects\TCMailTemplate;

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
      [
        'type' => 'text',
        'value' => $template->mail_template_name,
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_edit_mail_template'].'&mail_template_id='.$template->mail_template_id,
        'value' => 'Edit',
      ],
    ];

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
