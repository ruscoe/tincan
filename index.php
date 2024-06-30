<?php

use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\template\TCTemplate;
use TinCan\user\TCUserSession;
use TinCan\template\TCURL;
use TinCan\objects\TCPage;

// Tin Can Forum version.
define('TC_VERSION', '0.15');

/**
 * Forum entry point.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

// Composer autoload.
if (file_exists(getenv('TC_BASE_PATH').'/vendor/autoload.php')) {
    include getenv('TC_BASE_PATH').'/vendor/autoload.php';
} else {
    exit('Composer vendor autoload file is missing. You may need to run <b>composer install</b> in the root directory. See README.md for information.');
}

$db = new TCData();

// Test database connection.
if (!$db->test_connection()) {
    die('No database available. Check the connection information in your configuration file. See README.md for information.');
    exit;
}

try {
    $settings = $db->load_settings();
} catch (TCException $e) {
    // For now assume this means the site hasn't been installed.
    // Redirect to the installer.
    header('Location: '.TCURL::get_installer_url());
    exit;
}

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page_slug = null;
$page = null;

$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
$path = explode('?', $request_uri)[0];

$page_template = null;

if (('/' == $path) && empty($page_id)) {
    $page_template = 'front';
} elseif ($settings['enable_urls'] && empty($page_id) && !empty($path)) {
    // Work out page ID from friendly URL.
    $base_urls_to_page_ids = [
      $settings['base_url_board_groups'] => $settings['page_board_group'],
      $settings['base_url_boards'] => $settings['page_board'],
      $settings['base_url_threads'] => $settings['page_thread'],
      $settings['base_url_users'] => $settings['page_user'],
      $settings['base_url_new_thread'] => $settings['page_new_thread'],
      $settings['base_url_edit_post'] => $settings['page_edit_post'],
      $settings['base_url_delete_post'] => $settings['page_delete_post'],
      $settings['base_url_avatar'] => $settings['page_user_avatar'],
      'log-in' => $settings['page_log_in'],
      'log-out' => $settings['page_log_out'],
      'create-account' => $settings['page_create_account'],
      'reset-password' => $settings['page_reset_password'],
    ];

    // Attempt to match a base URL with the current path.
    foreach ($base_urls_to_page_ids as $base_url => $base_page_id) {
        // Start regex with initial slash.
        $regex_prefix = '#^/';
        // End regex with optional trailing slash or slash with question mark to
        // support URL parameters. Do not accept any other appendages.
        // Case insensitive.
        $regex_suffix = '(/?|(/\?)?)#i';
        // Replace the %slug% token with the regex string.
        $path_regex = $regex_prefix.str_replace('%slug%', '([a-z_\-0-9]*)', $base_url).$regex_suffix;

        $page_matches = null;
        preg_match($path_regex, $path, $page_matches);

        // We have a match! Set the page ID and slug to be used by the template.
        if (!empty($page_matches)) {
            $page_id = $base_page_id;
            $page_slug = (isset($page_matches[1])) ? $page_matches[1] : null;
        }
    }

    if (empty($page_id)) {
        // Page not found, redirect to 404 error page.
        header('Location: '.TCURL::create_url($settings['page_404']));
        exit;
    }
}

// Get page template if available, otherwise default to 404.
if (!empty($page_id)) {
    $page = $db->load_object(new TCPage(), $page_id);

    if (!empty($page)) {
        $page_template = $page->template;
    }
}

if (empty($page_template)) {
    // Page not found, redirect to 404 error page.
    // Potentially troublesome as any missing file will end up here,
    // not just forum URLs. Start debugging here if it becomes a problem.
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Render page.
TCTemplate::render('page/'.$page_template, $settings['theme'], ['page' => $page, 'settings' => $settings, 'user' => $user, 'slug' => $page_slug]);
TCTemplate::render('footer', $settings['theme'], null);
