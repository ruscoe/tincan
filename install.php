<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCPage;
use TinCan\TCPost;
use TinCan\TCRole;
use TinCan\TCSetting;
use TinCan\TCThread;
use TinCan\TCUser;

/**
 * Installs Tin Can Forum with optional test data.
 *
 * Configure database credentials in tc-config.php first.
 *
 * == DO NOT LEAVE THIS FILE ON A PUBLICLY ACCESSIBLE SERVER ==
 *
 * You should run this installer on a local dev environment then export
 * the database, copy the exported database to your production server,
 * and import it there.
 *
 * If you need to run the installer in production, delete this file afterwards.
 */
require 'tc-config.php';

require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

global $db;

$run_install = filter_input(INPUT_POST, 'run_install', FILTER_SANITIZE_NUMBER_INT);
$create_test_data = filter_input(INPUT_POST, 'create_test_data', FILTER_SANITIZE_STRING);

$db = new TCData();

if (1 == $run_install) {
  tc_truncate_tables();
  tc_create_settings();
  tc_create_users();

  if (!empty($create_test_data)) {
    tc_create_roles();
    tc_create_pages();
    $new_board_group_ids = tc_create_board_groups();
    $new_board_ids = tc_create_boards($new_board_group_ids);
    $new_thread_ids = tc_create_threads($new_board_ids);
    tc_create_posts($new_thread_ids);
  }
} else {
  ?>

<h1>Tin Can Forum Installer</h1>

<?php if (tc_is_installed()) { ?>

  <div>
    <h2>Tin Can Forum is already installed!</h2>
    <p>If you run this installer, all your data will be erased.</p>
    <p>This cannot be undone.</p>
  </div>

<?php } ?>

<form action="/install.php" method="POST">
  <input type="checkbox" name="create_test_data" />
  <label for="create_test_data">Generate test data</label>
  <input type="hidden" name="run_install" value="1" />
  <input type="submit" name="submit_install" value="Install Tin Can Forum" />
</form>

<?php
}

function tc_is_installed()
{
  global $db;

  return (int) $db->count_objects(new TCSetting()) > 0;
}

function tc_truncate_tables()
{
  global $db;

  $tables = [
      'tc_board_groups',
      'tc_boards',
      'tc_pages',
      'tc_posts',
      'tc_roles',
      'tc_settings',
      'tc_threads',
      'tc_users',
    ];

  foreach ($tables as $table) {
    $db->run_query("TRUNCATE {$table}");
  }
}

function tc_create_settings()
{
  global $db;

  $settings = [
      [
        'setting_name' => 'forum_name',
        'type' => 'text',
        'title' => 'Forum name',
        'value' => 'Tin Can Forum',
        'required' => 1,
      ],
      [
        'setting_name' => 'date_format',
        'type' => 'text',
        'title' => 'Date format',
        'value' => 'F jS Y',
        'required' => 1,
      ],
      [
        'setting_name' => 'date_time_format',
        'type' => 'text',
        'title' => 'Date & time format',
        'value' => 'F jS Y H:i',
        'required' => 1,
      ],
      [
        'setting_name' => 'min_thread_title',
        'type' => 'text',
        'title' => 'Minimum thread title length',
        'value' => '8',
        'required' => 1,
      ],
      [
        'setting_name' => 'posts_per_page',
        'type' => 'text',
        'title' => 'Posts per page',
        'value' => 10,
        'required' => 1,
      ],
      [
        'setting_name' => 'threads_per_page',
        'type' => 'text',
        'title' => 'Threads per page',
        'value' => 10,
        'required' => 1,
      ],
      [
        // TODO: Create a select box setting type so role can be chosen by name.
        'setting_name' => 'default_user_role',
        'type' => 'text',
        'title' => 'Default user role',
        'value' => '1',
        'required' => 1,
      ],
      [
        'setting_name' => 'enable_js',
        'type' => 'bool',
        'title' => 'Enable JavaScript',
        'value' => 'true',
        'required' => 1,
      ],
      [
        'setting_name' => 'enable_css',
        'type' => 'bool',
        'title' => 'Enable CSS',
        'value' => 'true',
        'required' => 1,
      ],
      [
        'setting_name' => 'theme',
        'type' => 'text',
        'title' => 'Theme',
        'value' => 'tincan',
        'required' => 1,
      ],
    ];

  foreach ($settings as $setting) {
    $db->save_object(new TCSetting((object) $setting));
  }
}

function tc_create_roles()
{
  global $db;

  $user_allowed_actions = [
    TCUser::ACT_CREATE_POST,
    TCUser::ACT_CREATE_THREAD,
  ];

  $mod_allowed_actions = [
    TCUser::ACT_CREATE_POST,
    TCUser::ACT_CREATE_THREAD,
    TCUser::ACT_EDIT_ANY_POST,
    TCUser::ACT_EDIT_ANY_THREAD,
    TCUser::ACT_DELETE_ANY_POST,
    TCUser::ACT_DELETE_ANY_THREAD,
  ];

  $admin_allowed_actions = [
    TCUser::ACT_CREATE_POST,
    TCUser::ACT_CREATE_THREAD,
    TCUser::ACT_EDIT_ANY_POST,
    TCUser::ACT_EDIT_ANY_THREAD,
    TCUser::ACT_DELETE_ANY_POST,
    TCUser::ACT_DELETE_ANY_THREAD,
    TCUser::ACT_EDIT_ANY_USER,
    TCUser::ACT_ACCESS_ADMIN,
  ];

  $roles = [
    ['role_name' => 'User',          'allowed_actions' => implode(',', $user_allowed_actions)],
    ['role_name' => 'Moderator',     'allowed_actions' => implode(',', $mod_allowed_actions)],
    ['role_name' => 'Administrator', 'allowed_actions' => implode(',', $admin_allowed_actions)],
  ];

  foreach ($roles as $role) {
    $db->save_object(new TCRole((object) $role));
  }
}

function tc_create_pages()
{
  global $db;

  $pages = [
      ['page_title' => 'Front Page',             'template' => 'front'],
      ['page_title' => 'Board Group',            'template' => 'board-group'],
      ['page_title' => 'Board',                  'template' => 'board'],
      ['page_title' => 'Thread',                 'template' => 'thread'],
      ['page_title' => 'User',                   'template' => 'user'],
      ['page_title' => 'User Avatar',            'template' => 'user-avatar'],
      ['page_title' => 'Create Account',         'template' => 'create-account'],
      ['page_title' => 'Log In',                 'template' => 'log-in'],
      ['page_title' => 'Log Out',                'template' => 'log-out'],
      ['page_title' => 'New Thread',             'template' => 'new-thread'],
      ['page_title' => 'Edit Post',              'template' => 'edit-post'],
      ['page_title' => 'Delete Post',            'template' => 'delete-post'],
      ['page_title' => 'Edit Thread',            'template' => 'edit-thread'],
      ['page_title' => 'Delete Thread',          'template' => 'delete-thread'],
      ['page_title' => 'Post Deleted',           'template' => 'post-deleted'],
      ['page_title' => 'Thread Deleted',         'template' => 'thread-deleted'],
      ['page_title' => 'Admin Forum Settings',   'template' => 'forum-settings'],
      ['page_title' => 'Admin Log In',           'template' => 'log-in'],
      ['page_title' => 'Admin Log Out',          'template' => 'log-out'],
      ['page_title' => 'Admin Board Groups',     'template' => 'board-groups'],
      ['page_title' => 'Admin Boards',           'template' => 'boards'],
      ['page_title' => 'Admin Threads',          'template' => 'threads'],
      ['page_title' => 'Admin Posts',            'template' => 'posts'],
      ['page_title' => 'Admin Pages',            'template' => 'pages'],
      ['page_title' => 'Admin Users',            'template' => 'users'],
      ['page_title' => 'Admin Edit Board Group', 'template' => 'edit-board-group'],
      ['page_title' => 'Admin Edit Board',       'template' => 'edit-board'],
      ['page_title' => 'Admin Edit Page',        'template' => 'edit-page'],
      ['page_title' => 'Admin Edit Post',        'template' => 'edit-post'],
      ['page_title' => 'Admin Edit Thread',      'template' => 'edit-thread'],
      ['page_title' => 'Admin Edit User',        'template' => 'edit-user'],
    ];

  foreach ($pages as $page) {
    $page['created_time'] = time();
    $page['updated_time'] = time();
    $saved_page = $db->save_object(new TCPage((object) $page));

    // Each page has a matching setting to identify its purpose.
    $setting_prefix = ('Admin' == substr($saved_page->page_title, 0, 5)) ? 'admin_' : '';

    $setting_name = $setting_prefix.'page_'.str_replace('-', '_', $saved_page->template);

    $setting = [
          'setting_name' => $setting_name,
          'type' => 'page',
          'title' => $saved_page->page_title,
          'value' => $saved_page->page_id,
          'required' => 1,
        ];

    $db->save_object(new TCSetting((object) $setting));
  }
}

function tc_create_users()
{
  global $db;

  $user = new TCUser();

  $users = [
      [
        'username' => 'user',
        'email' => 'user+test@example.org',
        'password' => $user->get_password_hash('user'),
        'role_id' => 1, // Default user role.
      ],
      [
        'username' => 'mod',
        'email' => 'mod+test@example.org',
        'password' => $user->get_password_hash('mod'),
        'role_id' => 2, // Moderator user role.
      ],
      [
        'username' => 'admin',
        'email' => 'admin+test@example.org',
        'password' => $user->get_password_hash('admin'),
        'role_id' => 3, // Administrator user role.
      ],
    ];

  foreach ($users as $user) {
    $user['created_time'] = time();
    $user['updated_time'] = time();
    $db->save_object(new TCUser((object) $user));
  }
}

function tc_create_board_groups()
{
  global $db;

  $board_groups_to_create = 4;

  for ($i = 0; $i < $board_groups_to_create; ++$i) {
    $board_groups[] = ['board_group_name' => 'Board Group '.($i + 1)];
  }

  $new_board_group_ids = [];

  foreach ($board_groups as $board_group) {
    $board_group['created_time'] = time();
    $board_group['updated_time'] = time();
    $new_board_group = $db->save_object(new TCBoardGroup((object) $board_group));

    $new_board_group_ids[] = $new_board_group->board_group_id;
  }

  return $new_board_group_ids;
}

function tc_create_boards($new_board_group_ids)
{
  global $db;

  $board_groups_to_create = 4;

  foreach ($new_board_group_ids as $board_group_id) {
    for ($i = 0; $i < $board_groups_to_create; ++$i) {
      $boards[] = ['board_group_id' => $board_group_id, 'board_name' => 'Board '.($i + 1), 'description' => tc_get_random_lipsum_short()];
    }
  }

  $new_board_ids = [];

  foreach ($boards as $board) {
    $board['created_time'] = time();
    $board['updated_time'] = time();
    $new_board = $db->save_object(new TCBoard((object) $board));

    $new_board_ids[] = $new_board->board_id;
  }

  return $new_board_ids;
}

function tc_create_threads($new_board_ids)
{
  global $db;

  $threads_to_create = 12;

  $threads = [];

  foreach ($new_board_ids as $board_id) {
    for ($i = 0; $i < $threads_to_create; ++$i) {
      $threads[] = ['board_id' => $board_id, 'thread_title' => tc_get_random_thread_title()];
    }
  }

  $new_thread_ids = [];

  foreach ($threads as $thread) {
    $thread['created_by_user'] = 1;
    $thread['updated_by_user'] = 1;
    $thread['created_time'] = time();
    $thread['updated_time'] = time();
    $new_thread = $db->save_object(new TCThread((object) $thread));

    $new_thread_ids[] = $new_thread->thread_id;
  }

  return $new_thread_ids;
}

function tc_create_posts($new_thread_ids)
{
  global $db;

  $posts_to_create = 24;

  $posts = [];

  foreach ($new_thread_ids as $thread_id) {
    for ($i = 0; $i < $posts_to_create; ++$i) {
      $posts[] = ['user_id' => 1, 'thread_id' => $thread_id, 'content' => tc_get_random_lipsum_long()];
    }
  }

  foreach ($posts as $post) {
    $post['created_time'] = time();
    $post['updated_time'] = time();
    $db->save_object(new TCPost((object) $post));
  }
}

function tc_get_random_thread_title()
{
  $titles = [
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
      'Nullam euismod faucibus ipsum, a porttitor odio eleifend eget.',
      'Donec et placerat nunc, quis tempus ipsum.',
      'Cras suscipit eros quis mauris cursus, vitae commodo lacus feugiat.',
      'Duis sed ipsum quis libero aliquam vestibulum et in quam.',
      'Aenean at dui vel dui aliquam venenatis et vitae turpis.',
    ];

  $index = rand(0, (count($titles) - 1));

  return $titles[$index];
}

function tc_get_random_lipsum_short()
{
  $lipsum = [
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
      'Nullam euismod faucibus ipsum, a porttitor odio eleifend eget.',
      'Donec et placerat nunc, quis tempus ipsum.',
      'Cras suscipit eros quis mauris cursus, vitae commodo lacus feugiat.',
      'Duis sed ipsum quis libero aliquam vestibulum et in quam.',
      'Aenean at dui vel dui aliquam venenatis et vitae turpis.',
    ];

  $index = rand(0, (count($lipsum) - 1));

  return $lipsum[$index];
}

function tc_get_random_lipsum_long()
{
  $lipsum = [
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed molestie nisi vel mi eleifend tincidunt. Ut dictum ornare quam ut imperdiet. Mauris justo ante, ullamcorper sed libero ac, tincidunt pellentesque sapien. Fusce ut vulputate libero, id mollis felis. Nullam euismod faucibus ipsum, a porttitor odio eleifend eget. Etiam sed lectus eu magna vestibulum finibus a et nunc. Fusce sit amet metus varius, malesuada mauris non, gravida urna.',
      'Nullam ut libero tellus. Quisque vel elementum metus, a pretium dolor. Nullam quis auctor enim. Nunc sagittis tincidunt sagittis. Nulla arcu urna, volutpat ut urna quis, suscipit auctor elit. Donec et placerat nunc, quis tempus ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent mollis dolor ac mi egestas, sit amet porta sem consequat. Etiam molestie purus felis, a convallis sem consequat eu. Duis facilisis pellentesque.',
      'Vivamus tempor euismod aliquam. Fusce tempus ultrices vestibulum. Praesent a sagittis justo. Sed ut molestie eros. Curabitur varius dolor non augue lobortis, id ullamcorper elit faucibus. Fusce pulvinar dictum diam id placerat. Vestibulum mattis enim ac tortor tincidunt, quis vulputate diam vehicula. Mauris euismod dui at elit congue pulvinar.',
      'Aenean ullamcorper at mi eget mollis. Cras in varius tellus. Praesent pretium, tortor mattis faucibus volutpat, nisl diam pharetra diam, et condimentum eros dui lobortis urna. Donec id velit at lorem ultricies volutpat sit amet at tortor. Aenean at dui vel dui aliquam venenatis et vitae turpis. In finibus mollis lectus non efficitur.',
      'Etiam molestie purus felis, a convallis sem consequat eu. Duis facilisis pellentesque dolor nec vehicula. Morbi pulvinar porta erat gravida commodo. Fusce enim turpis, laoreet ac dapibus nec, eleifend sit amet ipsum. Nunc eget libero lacinia enim interdum gravida. Nam gravida ut urna in dignissim.',
      'Phasellus suscipit sagittis lorem, nec tristique libero porta id. Vestibulum dictum libero eget augue blandit ultrices. Vestibulum a massa nulla. Suspendisse bibendum ante ac diam dictum, at laoreet est lobortis. Mauris facilisis lacinia purus, nec pharetra nisi ullamcorper vulputate.',
    ];

  $index = rand(0, (count($lipsum) - 1));

  return $lipsum[$index];
}
