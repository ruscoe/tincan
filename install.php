<?php

use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\objects\TCPage;
use TinCan\objects\TCPost;
use TinCan\objects\TCRole;
use TinCan\objects\TCSetting;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Installs Tin Can Forum with optional test data.
 *
 * == DO NOT LEAVE THIS FILE ON A PUBLICLY ACCESSIBLE SERVER ==
 *
 * You should run this installer on a local dev environment then export
 * the database, copy the exported database to your production server,
 * and import it there.
 *
 * If you need to run the installer in production, delete this file afterwards.
 */

require_once 'vendor/autoload.php';


global $db;

define('BOARD_GROUPS_TO_CREATE', 2);
define('BOARDS_TO_CREATE', 2);
define('THREADS_TO_CREATE', 1);
define('POSTS_TO_CREATE', 16);

$run_install = filter_input(INPUT_POST, 'run_install', FILTER_SANITIZE_NUMBER_INT);
$create_test_data = filter_input(INPUT_POST, 'create_test_data', FILTER_SANITIZE_STRING);
$admin_email = filter_input(INPUT_POST, 'admin_email', FILTER_SANITIZE_STRING);
$admin_password = filter_input(INPUT_POST, 'admin_password', FILTER_SANITIZE_STRING);
$base_url = filter_input(INPUT_POST, 'base_url', FILTER_SANITIZE_STRING);

$db = new TCData();

if (1 == $run_install) {
    tc_create_tables();
    tc_create_settings(['base_url' => $base_url, 'site_email_address' => $admin_email]);
    tc_create_roles();
    tc_create_pages();
    // TODO: Validate email and password.
    $admin_user = tc_create_admin_user($admin_email, $admin_password);
    tc_create_mail_templates();

    if (!empty($create_test_data)) {
        tc_create_users();
        $new_board_group_ids = tc_create_board_groups();
        $new_board_ids = tc_create_boards($new_board_group_ids);
        $new_thread_ids = tc_create_threads($new_board_ids);
        tc_create_posts($new_thread_ids);
    }

    $session = new TCUserSession();
    $session->create_session($admin_user);

    header('Location: /');
    exit;
} else {
    ?>

<html>
<head>
  <style type="text/css">

    div#content {
      color: #6B6666;
      font-family: Verdana, sans-serif;
      text-align: center;
    }

    h1 {
      font-size: 2rem;
      margin-top: 3rem;
    }

    h2 {
      font-size: 1rem;
    }

    div#installed, form#install-options {
      max-width: 600px;
    }

    div#installed {
      background-color: #F1F1F1;
      border: 2px solid #DDDDDD;
      margin: 0 auto 0 auto;
      margin-top: 3rem;
      padding: 1rem 0 1rem 0;
    }

    form#install-options {
      display: flex;
      flex-direction: column;
      margin: 0 auto 0 auto;
      padding-top: 2rem;
    }

    form#install-options .fieldset {
      display: flex;
      flex: 1;
      margin: 0.5rem 0 0.5rem 0;
    }

    form#install-options .fieldset.button {
      justify-content: flex-end;
      padding-top: 1rem;
    }

    form#install-options .field {
      background-color: #F1F1F1;
      flex: 1.5;
      padding: 0.25rem;
      text-align: left;
    }

    form#install-options label {
      background-color: #c9cebd;
      color: #000;
      display: block;
      flex: 1;
      padding: 0.25rem 0.5rem 0.25rem 0.5rem;
      text-align: left;
    }

    form#install-options input.text-input {
      width: 100%;
    }

  </style>
</head>
<body>
<div id="content">

<h1>Tin Can Forum Installer</h1>

    <?php
    $error = false;

    try {
        if (tc_is_installed()) {
            ?>

  <div id="installed">
    <h2>Tin Can Forum is already installed!</h2>
    <p>If you run this installer, all your data will be erased.</p>
    <p>This cannot be undone.</p>
  </div>

            <?php
        }
    } catch (TCException $e) {
        ?>
  <div id="error-box">
    <p>Unable to connect to the database. Please check your configuration.</p>
  </div>
        <?php
        $error = true;
    } ?>

    <?php if (!$error) { ?>
<form id="install-options" action="/install.php" method="POST">
  <div class="fieldset">
    <label for="create_test_data">Generate test data</label>
    <div class="field">
      <input type="checkbox" name="create_test_data" />
    </div>
  </div>

        <?php
        $user = new TCUser();
        $password = $user->generate_password();
        ?>

  <div class="fieldset">
    <label for="admin_username">Admin username</label>
    <div class="field">
      <input type="text" name="admin_username" value="admin" disabled />
    </div>
  </div>

  <div class="fieldset">
    <label for="admin_email">Admin email</label>
    <div class="field">
      <input type="text" name="admin_email" value="admin@example.org" />
    </div>
  </div>

  <div class="fieldset">
    <label for="admin_password">Admin password</label>
    <div class="field">
      <input type="text" name="admin_password" value="<?php echo $password; ?>" />
    </div>
  </div>

        <?php
        $base_url = (isset($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['HTTPS_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
        ?>

  <div class="fieldset">
    <label for="base_url">Base URL</label>
    <div class="field">
      <input type="text" name="base_url" value="<?php echo $base_url; ?>" />
    </div>
  </div>

  <input type="hidden" name="run_install" value="1" />

  <div class="fieldset button">
    <input type="submit" name="submit_install" value="Install Tin Can Forum" />
  </div>
</form>
    <?php } ?>

</div>
</body>
</html>

    <?php
}

function tc_is_installed()
{
    global $db;

    $result = $db->run_query("SELECT count(*) AS `count` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = '".getenv('TC_DB_NAME')."' AND `TABLE_NAME` = 'tc_settings'");
    $row = $result->fetch_object();

    return 0 !== (int) $row->count;
}

function tc_create_tables()
{
    global $db;

    $queries = [
      'DROP TABLE IF EXISTS `tc_board_groups`',

      "CREATE TABLE `tc_board_groups` (
      `board_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `board_group_name` varchar(255) NOT NULL DEFAULT '',
      `weight` int(10) NOT NULL,
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`board_group_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_boards`',

      "CREATE TABLE `tc_boards` (
      `board_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `board_name` varchar(255) NOT NULL DEFAULT '',
      `board_group_id` bigint(20) unsigned NOT NULL,
      `description` mediumtext NOT NULL,
      `weight` int(10) NOT NULL,
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`board_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_pages`',

      "CREATE TABLE `tc_pages` (
      `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `page_title` varchar(255) NOT NULL DEFAULT '',
      `template` varchar(255) NOT NULL DEFAULT '',
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      `required` tinyint(1) unsigned NOT NULL,
      PRIMARY KEY (`page_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_posts`',

      'CREATE TABLE `tc_posts` (
      `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) unsigned NOT NULL,
      `thread_id` bigint(20) unsigned NOT NULL,
      `content` longtext NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      `created_time` int(10) unsigned NOT NULL,
      `updated_by_user` bigint(20) unsigned NOT NULL,
      `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
      PRIMARY KEY (`post_id`)
    ) AUTO_INCREMENT=1000',

      'DROP TABLE IF EXISTS `tc_roles`',

      "CREATE TABLE `tc_roles` (
      `role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `role_name` varchar(255) NOT NULL DEFAULT '',
      `allowed_actions` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`role_id`)
    )",

      'DROP TABLE IF EXISTS `tc_roles_actions`',

      'CREATE TABLE `tc_roles_actions` (
      `role_id` bigint(20) unsigned NOT NULL,
      `action_id` bigint(20) unsigned NOT NULL,
      PRIMARY KEY (`role_id`,`action_id`)
    )',

      'DROP TABLE IF EXISTS `tc_sessions`',

      "CREATE TABLE `tc_sessions` (
      `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) unsigned NOT NULL,
      `hash` varchar(255) NOT NULL DEFAULT '',
      `created_time` int(10) unsigned NOT NULL,
      `expiration_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`session_id`),
      KEY `HASH_INDEX` (`hash`)
    )",

      'DROP TABLE IF EXISTS `tc_settings`',

      "CREATE TABLE `tc_settings` (
      `setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `setting_name` varchar(255) NOT NULL DEFAULT '',
      `category` varchar(16) NOT NULL DEFAULT '',
      `type` varchar(16) NOT NULL DEFAULT '',
      `title` varchar(255) NOT NULL DEFAULT '',
      `value` varchar(255) NOT NULL DEFAULT '',
      `required` tinyint(1) unsigned NOT NULL,
      PRIMARY KEY (`setting_id`)
    )",

      'DROP TABLE IF EXISTS `tc_threads`',

      "CREATE TABLE `tc_threads` (
      `thread_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `board_id` bigint(20) unsigned NOT NULL,
      `thread_title` varchar(255) NOT NULL DEFAULT '',
      `first_post_id` bigint(20) unsigned NOT NULL,
      `created_by_user` bigint(20) unsigned NOT NULL,
      `updated_by_user` bigint(20) unsigned NOT NULL,
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
      `pinned` tinyint(1) unsigned NOT NULL DEFAULT 0,
      `locked` tinyint(1) unsigned NOT NULL DEFAULT 0,
      PRIMARY KEY (`thread_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_users`',

      "CREATE TABLE `tc_users` (
      `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(255) NOT NULL DEFAULT '',
      `email` varchar(255) NOT NULL DEFAULT '',
      `password` varchar(255) NOT NULL DEFAULT '',
      `password_reset_code` varchar(255) NOT NULL DEFAULT '',
      `role_id` bigint(20) unsigned NOT NULL,
      `avatar` varchar(255) NOT NULL DEFAULT '',
      `suspended` tinyint(1) unsigned NOT NULL,
      `signup_ip` varchar(255) NOT NULL DEFAULT '',
      `last_ip` varchar(255) NOT NULL DEFAULT '',
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`user_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_mail_templates`',

      "CREATE TABLE `tc_mail_templates` (
      `mail_template_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `mail_template_name` varchar(255) NOT NULL DEFAULT '',
      `content` longtext NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      `created_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`mail_template_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_pending_users`',

      "CREATE TABLE `tc_pending_users` (
      `pending_user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) unsigned NOT NULL,
      `confirmation_code` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`pending_user_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_reports`',

      "CREATE TABLE `tc_reports` (
      `report_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` bigint(20) unsigned NOT NULL,
      `post_id` bigint(20) unsigned NOT NULL,
      `reason` varchar(255) NOT NULL DEFAULT '',
      `created_time` int(10) unsigned NOT NULL,
      `updated_time` int(10) unsigned NOT NULL,
      PRIMARY KEY (`report_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_attachments`',

      "CREATE TABLE `tc_attachments` (
      `attachment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `post_id` bigint(20) unsigned NOT NULL,
      `file_path` varchar(255) NOT NULL DEFAULT '',
      `thumbnail_file_path` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`attachment_id`),
      KEY `POST_INDEX` (`post_id`)
    ) AUTO_INCREMENT=1000",

      'DROP TABLE IF EXISTS `tc_banned_ips`',

      "CREATE TABLE `tc_banned_ips` (
      `banned_ip_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `ip` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`banned_ip_id`)
    )",

      'DROP TABLE IF EXISTS `tc_banned_emails`',

      "CREATE TABLE `tc_banned_emails` (
      `banned_email_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `email` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`banned_email_id`)
    )",
    ];

    foreach ($queries as $query) {
        $db->run_query($query);
    }
}

function tc_create_settings($install_settings = [])
{
    global $db;

    $settings = [
        [
          'setting_name' => 'forum_name',
          'category' => 'forum',
          'type' => 'text',
          'title' => 'Forum name',
          'value' => 'Tin Can Forum',
        ],
        [
          'setting_name' => 'forum_tagline',
          'category' => 'forum',
          'type' => 'text',
          'title' => 'Forum tagline',
          'value' => 'Join the discussion!',
        ],
        [
          'setting_name' => 'forum_logo',
          'category' => 'forum',
          'type' => 'image',
          'title' => 'Forum logo',
          'value' => '/assets/images/tin-can-logo.png',
        ],
        [
          'setting_name' => 'base_url',
          'category' => 'forum',
          'type' => 'text',
          'title' => 'Forum base URL',
          'value' => $install_settings['base_url'],
        ],
        [
          'setting_name' => 'date_format',
          'category' => 'date',
          'type' => 'text',
          'title' => 'Date format',
          'value' => 'F jS Y',
        ],
        [
          'setting_name' => 'date_time_format',
          'category' => 'date',
          'type' => 'text',
          'title' => 'Date & time format',
          'value' => 'F jS Y H:i',
        ],
        [
          'setting_name' => 'min_thread_title',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Minimum thread title length',
          'value' => '8',
        ],
        [
          'setting_name' => 'max_thread_title',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Maximum thread title length',
          'value' => '255',
        ],
        [
          'setting_name' => 'posts_per_page',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Posts per page',
          'value' => 10,
        ],
        [
          'setting_name' => 'threads_per_page',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Threads per page',
          'value' => 10,
        ],
        [
          'setting_name' => 'attachment_limit',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Maximum number of attachments per post',
          'value' => 10,
        ],
        [
          'setting_name' => 'attachment_scale',
          'category' => 'content',
          'type' => 'text',
          'title' => 'Scale attached images to this pixel width',
          'value' => 1200,
        ],
        [
          'setting_name' => 'allow_registration',
          'category' => 'user',
          'type' => 'bool',
          'title' => 'Allow new registrations',
          'value' => 'true',
        ],
        [
          'setting_name' => 'default_user_role',
          'category' => 'user',
          'type' => 'role',
          'title' => 'Default user role',
          'value' => '1',
        ],
        [
          'setting_name' => 'theme',
          'category' => 'theme',
          'type' => 'text',
          'title' => 'Theme',
          'value' => 'tincan',
        ],
        [
          'setting_name' => 'enable_css',
          'category' => 'theme',
          'type' => 'bool',
          'title' => 'Enable CSS',
          'value' => 'true',
        ],
        [
          'setting_name' => 'enable_js',
          'category' => 'theme',
          'type' => 'bool',
          'title' => 'Enable JavaScript',
          'value' => 'true',
        ],
        [
          'setting_name' => 'enable_theme_debug',
          'category' => 'theme',
          'type' => 'bool',
          'title' => 'Enable theme debug',
          'value' => 'false',
        ],
        [
          'setting_name' => 'site_email_name',
          'category' => 'email',
          'type' => 'text',
          'title' => 'Site email sender name',
          'value' => 'Tin Can Forum',
        ],
        [
          'setting_name' => 'site_email_address',
          'category' => 'email',
          'type' => 'text',
          'title' => 'Site email sender address',
          'value' => $install_settings['site_email_address'],
        ],
        [
          'setting_name' => 'smtp_host',
          'category' => 'email',
          'type' => 'text',
          'title' => 'SMTP host',
          'value' => '',
        ],
        [
          'setting_name' => 'smtp_user',
          'category' => 'email',
          'type' => 'text',
          'title' => 'SMTP username',
          'value' => '',
        ],
        [
          'setting_name' => 'smtp_pass',
          'category' => 'email',
          'type' => 'text',
          'title' => 'SMTP password',
          'value' => '',
        ],
        [
          'setting_name' => 'smtp_port',
          'category' => 'email',
          'type' => 'text',
          'title' => 'SMTP port',
          'value' => '465',
        ],
        [
          'setting_name' => 'smtp_enable_tls',
          'category' => 'email',
          'type' => 'bool',
          'title' => 'Enable implicit TLS encryption',
          'value' => 'true',
        ],
        [
          'setting_name' => 'smtp_enable_verbose',
          'category' => 'email',
          'type' => 'bool',
          'title' => 'Enable verbose debug output',
          'value' => 'false',
        ],
        [
          'setting_name' => 'require_confirm_email',
          'category' => 'email',
          'type' => 'bool',
          'title' => 'Require account confirmation by email',
          'value' => 'false',
        ],
        [
          'setting_name' => 'mail_reset_password',
          'category' => 'email',
          'type' => 'mail_template',
          'title' => 'Reset Password Mail Template',
          'value' => 1000,
        ],
        [
          'setting_name' => 'mail_confirm_account',
          'category' => 'email',
          'type' => 'mail_template',
          'title' => 'Confirm Account Mail Template',
          'value' => 1001,
        ],
      ];

    foreach ($settings as $setting) {
        // All default settings are required and cannot be deleted.
        $setting['required'] = 1;

        try {
            $db->save_object(new TCSetting((object) $setting));
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }
    }
}

function tc_create_roles()
{
    global $db;

    $user_allowed_actions = [
      TCUser::ACT_CREATE_POST,
      TCUser::ACT_CREATE_THREAD,
      TCUser::ACT_REPORT_ANY_POST,
    ];

    $mod_allowed_actions = [
      TCUser::ACT_CREATE_POST,
      TCUser::ACT_CREATE_THREAD,
      TCUser::ACT_EDIT_ANY_POST,
      TCUser::ACT_EDIT_ANY_THREAD,
      TCUser::ACT_DELETE_ANY_POST,
      TCUser::ACT_DELETE_ANY_THREAD,
      TCUser::ACT_REPORT_ANY_POST,
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
      TCUser::ACT_REPORT_ANY_POST,
    ];

    $roles = [
      ['role_name' => 'User',          'allowed_actions' => implode(',', $user_allowed_actions)],
      ['role_name' => 'Moderator',     'allowed_actions' => implode(',', $mod_allowed_actions)],
      ['role_name' => 'Administrator', 'allowed_actions' => implode(',', $admin_allowed_actions)],
    ];

    foreach ($roles as $role) {
        try {
            $db->save_object(new TCRole((object) $role));
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }
    }
}

function tc_create_pages()
{
    global $db;

    $pages = [
        ['page_title' => 'Page Not Found',             'template' => '404'],
        ['page_title' => 'Front Page',                 'template' => 'front'],
        ['page_title' => 'Board Group',                'template' => 'board-group'],
        ['page_title' => 'Board',                      'template' => 'board'],
        ['page_title' => 'Thread',                     'template' => 'thread'],
        ['page_title' => 'User',                       'template' => 'user'],
        ['page_title' => 'Edit User',                  'template' => 'edit-user'],
        ['page_title' => 'User Avatar',                'template' => 'user-avatar'],
        ['page_title' => 'Create Account',             'template' => 'create-account'],
        ['page_title' => 'Confirm Account',            'template' => 'confirm-account'],
        ['page_title' => 'Log In',                     'template' => 'log-in'],
        ['page_title' => 'Log Out',                    'template' => 'log-out'],
        ['page_title' => 'Reset Password',             'template' => 'reset-password'],
        ['page_title' => 'Set Password',               'template' => 'set-password'],
        ['page_title' => 'New Thread',                 'template' => 'new-thread'],
        ['page_title' => 'Edit Thread',                'template' => 'edit-thread'],
        ['page_title' => 'Delete Thread',              'template' => 'delete-thread'],
        ['page_title' => 'Thread Deleted',             'template' => 'thread-deleted'],
        ['page_title' => 'Edit Post',                  'template' => 'edit-post'],
        ['page_title' => 'Delete Post',                'template' => 'delete-post'],
        ['page_title' => 'Post Deleted',               'template' => 'post-deleted'],
        ['page_title' => 'Report Post',                'template' => 'report-post'],
        ['page_title' => 'Post Reported',              'template' => 'post-reported'],
        ['page_title' => 'Admin Forum Status',         'template' => 'forum-status'],
        ['page_title' => 'Admin Forum Settings',       'template' => 'forum-settings'],
        ['page_title' => 'Admin Log In',               'template' => 'log-in'],
        ['page_title' => 'Admin Log Out',              'template' => 'log-out'],
        ['page_title' => 'Admin Board Groups',         'template' => 'board-groups'],
        ['page_title' => 'Admin Boards',               'template' => 'boards'],
        ['page_title' => 'Admin Threads',              'template' => 'threads'],
        ['page_title' => 'Admin Users',                'template' => 'users'],
        ['page_title' => 'Admin Posts',                'template' => 'posts'],
        ['page_title' => 'Admin Mail Templates',       'template' => 'mail-templates'],
        ['page_title' => 'Admin Edit Board Group',     'template' => 'edit-board-group'],
        ['page_title' => 'Admin Delete Board Group',   'template' => 'delete-board-group'],
        ['page_title' => 'Admin Edit Board',           'template' => 'edit-board'],
        ['page_title' => 'Admin Delete Board',         'template' => 'delete-board'],
        ['page_title' => 'Admin Edit Thread',          'template' => 'edit-thread'],
        ['page_title' => 'Admin Delete Thread',        'template' => 'delete-thread'],
        ['page_title' => 'Admin Edit User',            'template' => 'edit-user'],
        ['page_title' => 'Admin Delete User',          'template' => 'delete-user'],
        ['page_title' => 'Admin Edit Mail Template',   'template' => 'edit-mail-template'],
        ['page_title' => 'Admin Upload Setting Image', 'template' => 'upload-setting-image'],
        ['page_title' => 'Admin Delete Setting Image', 'template' => 'delete-setting-image'],
        ['page_title' => 'Admin Test Email',           'template' => 'test-mail'],
        ['page_title' => 'Admin Reported Posts',       'template' => 'reports'],
        ['page_title' => 'Admin Delete Report',        'template' => 'delete-report'],
        ['page_title' => 'Admin Banned IPs',           'template' => 'banned-ips'],
        ['page_title' => 'Admin Banned Emails',        'template' => 'banned-emails'],
      ];

    foreach ($pages as $page) {
        $page['created_time'] = time();
        $page['updated_time'] = time();
        // All default pages are required and cannot be deleted.
        $page['required'] = 1;

        try {
            $saved_page = $db->save_object(new TCPage((object) $page));
            $db->save_object($saved_page);
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }

        // Each page has a matching setting to identify its purpose.
        $setting_prefix = ('Admin' == substr($saved_page->page_title, 0, 5)) ? 'admin_' : '';

        $setting_name = $setting_prefix.'page_'.str_replace('-', '_', $saved_page->template);

        $setting = [
              'setting_name' => $setting_name,
              'category' => '',
              'type' => 'page',
              'title' => $saved_page->page_title,
              'value' => $saved_page->page_id,
              'required' => 1,
            ];

        try {
            $db->save_object(new TCSetting((object) $setting));
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }
    }
}

function tc_create_users()
{
    global $db;

    $user = new TCUser();

    $users = [
      [
        'username' => 'Manny',
        'email' => 'manny@example.org',
        'password' => $user->get_password_hash('manny'),
        'role_id' => 2, // Moderator user role.
        'avatar' => '/assets/images/sample-avatars/0_0.png',
      ],
      [
        'username' => 'Meche',
        'email' => 'meche@example.org',
        'password' => $user->get_password_hash('meche'),
        'role_id' => 1, // User role.
        'avatar' => '/assets/images/sample-avatars/0_1.png',
      ],
      [
        'username' => 'Domino',
        'email' => 'domino@example.org',
        'password' => $user->get_password_hash('domino'),
        'role_id' => 1, // User role.
        'avatar' => '/assets/images/sample-avatars/0_2.png',
      ],
    ];

    foreach ($users as $user_data) {
        $user_data['password_reset_code'] = '';
        $user_data['suspended'] = 0;
        $user_data['created_time'] = time();
        $user_data['updated_time'] = time();

        try {
            $new_user = $db->save_object(new TCUser((object) $user_data));
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }
    }
}

function tc_create_admin_user($email, $password)
{
    global $db;

    $user = new TCUser();
    $user->username = 'admin';
    $user->email = $email;
    $user->password = $user->get_password_hash($password);
    $user->role_id = 3; // Administrator user role.
    $user->suspended = 0;
    $user->created_time = time();
    $user->updated_time = time();

    try {
        $saved_user = $db->save_object(new TCUser((object) $user));
    } catch (TCException $e) {
        echo $e->getMessage()."\n";
    }

    return $saved_user;
}

function tc_create_board_groups()
{
    global $db;

    for ($i = 0; $i < BOARD_GROUPS_TO_CREATE; ++$i) {
        $board_groups[] = ['board_group_name' => 'Board Group '.($i + 1)];
    }

    $new_board_group_ids = [];

    foreach ($board_groups as $board_group) {
        $board_group['weight'] = 0;
        $board_group['created_time'] = time();
        $board_group['updated_time'] = time();

        try {
            $new_board_group = $db->save_object(new TCBoardGroup((object) $board_group));
            $db->save_object($new_board_group);
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }

        $new_board_group_ids[] = $new_board_group->board_group_id;
    }

    return $new_board_group_ids;
}

function tc_create_boards($new_board_group_ids)
{
    global $db;

    foreach ($new_board_group_ids as $board_group_id) {
        for ($i = 0; $i < BOARDS_TO_CREATE; ++$i) {
            $boards[] = [
              'board_group_id' => $board_group_id,
              'board_name' => tc_get_random_board_name(),
              'description' => tc_get_random_lipsum_short(),
            ];
        }
    }

    $new_board_ids = [];

    foreach ($boards as $board) {
        $board['weight'] = 0;
        $board['created_time'] = time();
        $board['updated_time'] = time();

        try {
            $new_board = $db->save_object(new TCBoard((object) $board));
            $db->save_object($new_board);
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }

        $new_board_ids[] = $new_board->board_id;
    }

    return $new_board_ids;
}

function tc_create_threads($new_board_ids)
{
    global $db;

    $threads = [];

    foreach ($new_board_ids as $board_id) {
        for ($i = 0; $i < THREADS_TO_CREATE; ++$i) {
            $threads[] = ['board_id' => $board_id, 'thread_title' => tc_get_random_thread_title()];
        }
    }

    $new_thread_ids = [];

    foreach ($threads as $thread) {
        $thread['created_by_user'] = 1000;
        $thread['updated_by_user'] = 1000;
        $thread['first_post_id'] = 1000;
        $thread['created_time'] = time();
        $thread['updated_time'] = time();

        try {
            $new_thread = $db->save_object(new TCThread((object) $thread));
            $db->save_object($new_thread);
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }

        $new_thread_ids[] = $new_thread->thread_id;
    }

    return $new_thread_ids;
}

function tc_create_posts($new_thread_ids)
{
    global $db;

    $user_ids = [
      '1000', '1001', '1002', '1003',
    ];

    $posts = [];

    foreach ($new_thread_ids as $thread_id) {
        for ($i = 0; $i < POSTS_TO_CREATE; ++$i) {
            $posts[] = [
              'user_id' => $user_ids[array_rand($user_ids, 1)],
              'thread_id' => $thread_id,
              'content' => tc_get_random_lipsum_long(),
            ];
        }
    }

    foreach ($posts as $post) {
        $post['created_time'] = time();
        $post['updated_time'] = time();
        $post['updated_by_user'] = $post['user_id'];

        try {
            $db->save_object(new TCPost((object) $post));
        } catch (TCException $e) {
            echo $e->getMessage()."\n";
        }
    }
}

function tc_create_mail_templates()
{
    global $db;

    $time = time();

    $queries = [
      "INSERT INTO `tc_mail_templates` (
      `mail_template_name`,
      `content`,
      `created_time`,
      `updated_time`
    ) VALUES (
      'Reset Password',
      'Your password reset link is\n{url}',
      {$time},
      {$time}
    )",
      "INSERT INTO `tc_mail_templates` (
      `mail_template_name`,
      `content`,
      `created_time`,
      `updated_time`
    ) VALUES (
      'Confirm Account',
      'Your account confirmation link is\n{url}',
      {$time},
      {$time}
    )",
    ];

    foreach ($queries as $query) {
        $db->run_query($query);
    }
}

function tc_get_random_board_name()
{
    $names = [
        'Red',
        'Blue',
        'Yellow',
        'Green',
        'Orange',
        'Purple',
        'Silver',
        'Gold',
      ];

    $index = rand(0, (count($names) - 1));

    return $names[$index].' Board';
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
