<?php

require 'tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';
require TC_BASE_PATH . '/includes/include-template.php';
require TC_BASE_PATH . '/includes/include-user.php';

function get_random_lipsum_short()
{
    $lipsum = array(
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'Nullam euismod faucibus ipsum, a porttitor odio eleifend eget.',
    'Donec et placerat nunc, quis tempus ipsum.',
    'Cras suscipit eros quis mauris cursus, vitae commodo lacus feugiat.',
    'Duis sed ipsum quis libero aliquam vestibulum et in quam.',
    'Aenean at dui vel dui aliquam venenatis et vitae turpis.'
  );

    $index = rand(0, (count($lipsum) - 1));

    return $lipsum[$index];
}

function get_random_lipsum_long()
{
    $lipsum = array(
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed molestie nisi vel mi eleifend tincidunt. Ut dictum ornare quam ut imperdiet. Mauris justo ante, ullamcorper sed libero ac, tincidunt pellentesque sapien. Fusce ut vulputate libero, id mollis felis. Nullam euismod faucibus ipsum, a porttitor odio eleifend eget. Etiam sed lectus eu magna vestibulum finibus a et nunc. Fusce sit amet metus varius, malesuada mauris non, gravida urna.',
    'Nullam ut libero tellus. Quisque vel elementum metus, a pretium dolor. Nullam quis auctor enim. Nunc sagittis tincidunt sagittis. Nulla arcu urna, volutpat ut urna quis, suscipit auctor elit. Donec et placerat nunc, quis tempus ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent mollis dolor ac mi egestas, sit amet porta sem consequat. Etiam molestie purus felis, a convallis sem consequat eu. Duis facilisis pellentesque.',
    'Vivamus tempor euismod aliquam. Fusce tempus ultrices vestibulum. Praesent a sagittis justo. Sed ut molestie eros. Curabitur varius dolor non augue lobortis, id ullamcorper elit faucibus. Fusce pulvinar dictum diam id placerat. Vestibulum mattis enim ac tortor tincidunt, quis vulputate diam vehicula. Mauris euismod dui at elit congue pulvinar.',
    'Aenean ullamcorper at mi eget mollis. Cras in varius tellus. Praesent pretium, tortor mattis faucibus volutpat, nisl diam pharetra diam, et condimentum eros dui lobortis urna. Donec id velit at lorem ultricies volutpat sit amet at tortor. Aenean at dui vel dui aliquam venenatis et vitae turpis. In finibus mollis lectus non efficitur.',
    'Etiam molestie purus felis, a convallis sem consequat eu. Duis facilisis pellentesque dolor nec vehicula. Morbi pulvinar porta erat gravida commodo. Fusce enim turpis, laoreet ac dapibus nec, eleifend sit amet ipsum. Nunc eget libero lacinia enim interdum gravida. Nam gravida ut urna in dignissim.',
    'Phasellus suscipit sagittis lorem, nec tristique libero porta id. Vestibulum dictum libero eget augue blandit ultrices. Vestibulum a massa nulla. Suspendisse bibendum ante ac diam dictum, at laoreet est lobortis. Mauris facilisis lacinia purus, nec pharetra nisi ullamcorper vulputate.'
  );

    $index = rand(0, (count($lipsum) - 1));

    return $lipsum[$index];
}

$db = new TCData();

// === Truncate tables ===

$tables = array(
  'tc_board_groups',
  'tc_boards',
  'tc_pages',
  'tc_posts',
  'tc_roles',
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
  ),
  array(
    'setting_name' => 'posts_per_page',
    'type' => 'text',
    'title' => 'Posts per page',
    'value' => 10,
    'required' => 1
  ),
  array(
    'setting_name' => 'threads_per_page',
    'type' => 'text',
    'title' => 'Threads per page',
    'value' => 10,
    'required' => 1
  ),
  array(
    'setting_name' => 'default_user_role',
    'type' => 'text',
    'title' => 'Default user role',
    'value' => 'user',
    'required' => 1
  )
);

foreach ($settings as $setting) {
    $db->save_object(new TCSetting((object) $setting));
}

// === Create user roles ===

$roles = array(
  array('role_name' => 'User',          'allowed_actions' => TCUser::ACT_CREATE_POST . ',' . TCUser::ACT_CREATE_THREAD),
  array('role_name' => 'Moderator',     'allowed_actions' => TCUser::ACT_CREATE_POST . ',' . TCUser::ACT_CREATE_THREAD),
  array('role_name' => 'Administrator', 'allowed_actions' => TCUser::ACT_CREATE_POST . ',' . TCUser::ACT_CREATE_THREAD . ',' . TCUser::ACT_ACCESS_ADMIN)
);

foreach ($roles as $role) {
    $db->save_object(new TCRole((object) $role));
}

// === Create pages ===

$pages = array(
  array('page_title' => 'Front Page',             'template' => 'front'),
  array('page_title' => 'Board Group',            'template' => 'board-group'),
  array('page_title' => 'Board',                  'template' => 'board'),
  array('page_title' => 'Thread',                 'template' => 'thread'),
  array('page_title' => 'User',                   'template' => 'user'),
  array('page_title' => 'Create Account',         'template' => 'create-account'),
  array('page_title' => 'Log In',                 'template' => 'log-in'),
  array('page_title' => 'Log Out',                'template' => 'log-out'),
  array('page_title' => 'New Thread',             'template' => 'new-thread'),
  array('page_title' => 'Admin Forum Settings',   'template' => 'forum-settings'),
  array('page_title' => 'Admin Log In',           'template' => 'log-in'),
  array('page_title' => 'Admin Log Out',          'template' => 'log-out'),
  array('page_title' => 'Admin Board Groups',     'template' => 'board-groups'),
  array('page_title' => 'Admin Boards',           'template' => 'boards'),
  array('page_title' => 'Admin Threads',          'template' => 'threads'),
  array('page_title' => 'Admin Posts',            'template' => 'posts'),
  array('page_title' => 'Admin Pages',            'template' => 'pages'),
  array('page_title' => 'Admin Users',            'template' => 'users'),
  array('page_title' => 'Admin Edit Board Group', 'template' => 'edit-board-group'),
  array('page_title' => 'Admin Edit Board',       'template' => 'edit-board'),
  array('page_title' => 'Admin Edit Page',        'template' => 'edit-page'),
  array('page_title' => 'Admin Edit Post',        'template' => 'edit-post'),
  array('page_title' => 'Admin Edit Thread',      'template' => 'edit-thread'),
  array('page_title' => 'Admin Edit User',        'template' => 'edit-user')
);

foreach ($pages as $page) {
    $page['created_time'] = time();
    $page['updated_time'] = time();
    $saved_page = $db->save_object(new TCPage((object) $page));

    // Each page has a matching setting to identify its purpose.
    $setting_prefix = (substr($saved_page->page_title, 0, 5) == 'Admin') ? 'admin_' : '';

    $setting_name = $setting_prefix . 'page_' . str_replace('-', '_', $saved_page->template);

    $setting = array(
    'setting_name' => $setting_name,
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
    'username' => 'user',
    'email' => 'user+test@example.org',
    'password' => $user->get_password_hash('user'),
    'role_id' => 1 // Default user role.
  ),
  array(
    'username' => 'mod',
    'email' => 'mod+test@example.org',
    'password' => $user->get_password_hash('mod'),
    'role_id' => 2 // Moderator user role.
  ),
  array(
    'username' => 'admin',
    'email' => 'admin+test@example.org',
    'password' => $user->get_password_hash('admin'),
    'role_id' => 3 // Administrator user role.
  )
);

foreach ($users as $user) {
    $user['created_time'] = time();
    $user['updated_time'] = time();
    $db->save_object(new TCUser((object) $user));
}

// === Create board groups ===

$board_groups_to_create = 4;

for ($i = 0; $i < $board_groups_to_create; $i++) {
    $board_groups[] = array('board_group_name' => get_random_lipsum_short());
}

$new_board_group_ids = array();

foreach ($board_groups as $board_group) {
    $board_group['created_time'] = time();
    $board_group['updated_time'] = time();
    $new_board_group = $db->save_object(new TCBoardGroup((object) $board_group));

    $new_board_group_ids[] = $new_board_group->board_group_id;
}

// === Create boards ===

$board_groups_to_create = 4;

foreach ($new_board_group_ids as $board_group_id) {
    for ($i = 0; $i < $board_groups_to_create; $i++) {
        $boards[] = array('board_group_id' => $board_group_id, 'board_name' => get_random_lipsum_short(), 'description' => get_random_lipsum_short());
    }
}

$new_board_ids = array();

foreach ($boards as $board) {
    $board['created_time'] = time();
    $board['updated_time'] = time();
    $new_board = $db->save_object(new TCBoard((object) $board));

    $new_board_ids[] = $new_board->board_id;
}

// === Create threads ===

$threads_to_create = 12;

$threads = array();

foreach ($new_board_ids as $board_id) {
    for ($i = 0; $i < $threads_to_create; $i++) {
        $threads[] = array('board_id' => $board_id, 'thread_title' => get_random_lipsum_short());
    }
}

$new_thread_ids = array();

foreach ($threads as $thread) {
    $thread['created_by_user'] = 1;
    $thread['updated_by_user'] = 1;
    $thread['created_time'] = time();
    $thread['updated_time'] = time();
    $new_thread = $db->save_object(new TCThread((object) $thread));

    $new_thread_ids[] = $new_thread->thread_id;
}

// === Create posts ===

$posts_to_create = 24;

$posts = array();

foreach ($new_thread_ids as $thread_id) {
    for ($i = 0; $i < $posts_to_create; $i++) {
        $posts[] = array('user_id' => 1, 'thread_id' => $thread_id, 'content' => get_random_lipsum_long());
    }
}

foreach ($posts as $post) {
    $post['created_time'] = time();
    $post['updated_time'] = time();
    $db->save_object(new TCPost((object) $post));
}
