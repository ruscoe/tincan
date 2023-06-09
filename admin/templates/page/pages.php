<?php

use TinCan\Admin\TCAdminTemplate;
use TinCan\db\TCData;
use TinCan\objects\TCPage;

/**
 * Page template for admin page list.
 *
 * @since 0.01
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

$pages = $db->load_objects(new TCPage(), [], $conditions, $order);
?>

<div class="objects-nav">
  <a class="admin-button" href="/admin/index.php?page=<?php echo $settings['admin_page_edit_page']; ?>">New Page</a>
</div>

<table class="objects">
  <th>Page Name</th>
  <th colspan="3">&nbsp;</th>
<?php
foreach ($pages as $page) {
    $data = [
      [
        'type' => 'text',
        'value' => $page->page_title,
      ],
      [
        'type' => 'link',
        'url' => '/index.php?page='.$page->page_id,
        'value' => 'View',
      ],
      [
        'type' => 'link',
        'url' => '/admin/index.php?page='.$settings['admin_page_edit_page'].'&page_id='.$page->page_id,
        'value' => 'Edit',
      ],
    ];

    if ($page->is_required()) {
        $data[] = [
          'type' => 'text',
          'value' => '<s>Delete</s>',
        ];
    } else {
        $data[] = [
          'type' => 'link',
          'url' => '/admin/index.php?page='.$settings['admin_page_delete_object'].'&object_type=page&object_id='.$page->page_id,
          'value' => 'Delete',
        ];
    }

    TCAdminTemplate::render('table-row', $data);
}
?>
</table>
