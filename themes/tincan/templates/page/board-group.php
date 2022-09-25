<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCTemplate;
use TinCan\TCURL;
use TinCan\TCUser;

$settings = $data['settings'];
$page = $data['page'];
$slug = $data['slug'];
$user = $data['user'];

/**
 * Board group page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$board_group_id = filter_input(INPUT_GET, 'board_group', FILTER_SANITIZE_NUMBER_INT);

$board_group = null;

$db = new TCData();

if (!empty($board_group_id)) {
    $board_group = $db->load_object(new TCBoardGroup(), $board_group_id);
} elseif (!empty($slug)) {
    $matched_board_groups = $db->load_objects(new TCBoardGroup(), null, [['field' => 'slug', 'value' => $slug]]);
    $board_group = reset($matched_board_groups);
}

if (empty($board_group)) {
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

TCTemplate::render('header', $settings['theme'], ['page_title' => $board_group->get_name(), 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => $board_group, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $board_group->get_name(); ?></h1>

<?php

$settings = $db->load_settings();

// Get boards in this board group; order by board name.
$conditions = [
  [
    'field' => 'board_group_id',
    'value' => $board_group->board_group_id,
  ],
];

$order = [
  'field' => 'board_name',
  'direction' => 'ASC',
];

// TODO: Sorting and pagination.
$boards = $db->load_objects(new TCBoard(), [], $conditions, $order);

$board_url = null;
if (!empty($boards)) {
    foreach ($boards as $board) {
        $url_id = ($settings['enable_urls']) ? $settings['base_url_boards'] : $settings['page_board'];
        $board_url = TCURL::create_url($url_id, ['board' => $board->board_id], $settings['enable_urls'], $board->get_slug());

        $data = [
          'board' => $board,
          'url' => $board_url,
        ];

        TCTemplate::render('board-preview', $settings['theme'], $data);
    }
} else {
    $log_in_url = TCURL::create_url($settings['page_log_in']); ?>
  <div class="message-box">
    <p>No boards here!</p>
    <?php if (empty($user)) { ?>
    <p>If you are the administrator, you can <a href="<?php echo $log_in_url; ?>">log in and create some boards!</p>
    <?php } elseif ($user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) { ?>
      <p>You are the administrator! You can <a href="/admin">create some boards!</p>
    <?php } ?>
  </div>
<?php
}
