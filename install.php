<?php

require 'tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

$db = new TCData();

// === Truncate tables ===

$tables = array(
  'tc_board_groups',
  'tc_boards',
  'tc_pages',
  'tc_posts',
  'tc_settings',
  'tc_threads',
  'tc_users'
);

foreach ($tables as $table) {
  $db->run_query("TRUNCATE {$table}");
}

// === Create settings ===

$settings = array(
  array(
    'setting_name' => 'date_format',
    'type' => 'text',
    'title' => 'Date format',
    'value' => 'Y-m-d H:i:s',
    'required' => 1
  )
);

foreach ($settings as $setting) {
  $db->save_object(new TCSetting((object) $setting));
}

// === Create pages ===

$pages = array(
  array('page_title' => 'Front Page', 'template' => 'front'),
  array('page_title' => 'Board Group', 'template' => 'board-group'),
  array('page_title' => 'Board', 'template' => 'board'),
  array('page_title' => 'Thread', 'template' => 'thread'),
  array('page_title' => 'User', 'template' => 'user'),
  array('page_title' => 'Create Account', 'template' => 'create-account'),
  array('page_title' => 'Log In', 'template' => 'login'),
  array('page_title' => 'New Thread', 'template' => 'new-thread'),
  array('page_title' => 'Admin Board Groups', 'template' => 'board-groups'),
  array('page_title' => 'Admin Boards', 'template' => 'boards'),
  array('page_title' => 'Admin Threads', 'template' => 'threads'),
  array('page_title' => 'Admin Posts', 'template' => 'posts'),
  array('page_title' => 'Admin Pages', 'template' => 'pages'),
  array('page_title' => 'Admin Users', 'template' => 'users'),
  array('page_title' => 'Admin Login', 'template' => 'admin-login'),
  array('page_title' => 'Forum Settings', 'template' => 'forum-settings')
);

foreach ($pages as $page) {
  $page['created_time'] = time();
  $page['updated_time'] = time();
  $saved_page = $db->save_object(new TCPage((object) $page));

  // Each page has a matching setting to identify its purpose.
  $setting = array(
    'setting_name' => 'page_' . str_replace('-', '_', $saved_page->template),
    'type' => 'page',
    'title' => $saved_page->page_title,
    'value' => $saved_page->page_id,
    'required' => 1
  );

  $db->save_object(new TCSetting((object) $setting));
}

// === Create users ===

$user = new TCUser();

$users = array(
  array(
    'username' => 'admin',
    'email' => 'test@example.org',
    'password' => $user->get_password_hash('admin')
  )
);

foreach ($users as $user) {
  $user['created_time'] = time();
  $user['updated_time'] = time();
  $db->save_object(new TCUser((object) $user));
}

// === Create board groups ===

$board_groups = array(
  array('board_group_name' => 'Programming'),          // 1
  array('board_group_name' => 'Modal Building'),       // 2
  array('board_group_name' => 'Comic Books and Cards') // 3
);

foreach ($board_groups as $board_group) {
  $board_group['created_time'] = time();
  $board_group['updated_time'] = time();
  $db->save_object(new TCBoardGroup((object) $board_group));
}

// === Create boards ===

$boards = array(
  array('board_group_id' => 1, 'board_name' => 'General Programming'), // 1
  array('board_group_id' => 1, 'board_name' => 'Web Programming'),     // 2
  array('board_group_id' => 1, 'board_name' => 'Game Programming'),    // 3
  array('board_group_id' => 2, 'board_name' => 'Airfix'),              // 4
  array('board_group_id' => 2, 'board_name' => 'Miniature Painting'),  // 5
  array('board_group_id' => 2, 'board_name' => '3D Printing'),         // 6
  array('board_group_id' => 3, 'board_name' => 'Indie Comics'),        // 7
  array('board_group_id' => 3, 'board_name' => 'Marvel / DC'),         // 8
  array('board_group_id' => 3, 'board_name' => 'Trading Cards')        // 9
);

foreach ($boards as $board) {
  $board['created_time'] = time();
  $board['updated_time'] = time();
  $db->save_object(new TCBoard((object) $board));
}

// === Create threads ===

$threads = array(
  array('board_id' => 1, 'thread_title' => 'My favorite part of the Fibonacci sequence') // 1
);

foreach ($threads as $thread) {
  $thread['created_time'] = time();
  $thread['updated_time'] = time();
  $db->save_object(new TCThread((object) $thread));
}

// === Create posts ===

$posts = array(
  array('user_id' => 1, 'thread_id' => 1, 'content' => '8, 13, 21, 34, 55, 89')
);

foreach ($posts as $post) {
  $post['created_time'] = time();
  $post['updated_time'] = time();
  $db->save_object(new TCPost((object) $post));
}
