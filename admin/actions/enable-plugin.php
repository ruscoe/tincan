<?php

use TinCan\TCData;
use TinCan\TCPlugin;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Admin page plugin enabler.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../../tc-config.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-user.php';
require TC_BASE_PATH.'/core/template/class-tc-url.php';

$db = new TCData();
$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$settings['page_log_in']);
    exit;
}

$plugin_path = filter_input(INPUT_GET, 'plugin', FILTER_SANITIZE_STRING);

// Load config and create a new database record.
$config = file_get_contents(TC_PLUGINS_PATH.'/'.$plugin_path.'/plugin.json');

if (!empty($config)) {
    $decoded_config = json_decode($config);

    $plugin = new TCPlugin();
    $plugin->plugin_name = $decoded_config->name;
    $plugin->plugin_namespace = $decoded_config->namespace;
    $plugin->path = $plugin_path;
    $plugin->enabled = 1;

    $db->save_object($plugin);
} else {
    // TODO: Error handling.
}

$destination = '/admin/index.php?page='.$settings['admin_page_plugins'];

header('Location: '.$destination);
exit;
