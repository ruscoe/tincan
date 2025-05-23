<?php

use TinCan\db\TCData;
use TinCan\TCException;
use TinCan\template\TCTemplate;
use TinCan\user\TCUserSession;
use TinCan\template\TCURL;
use TinCan\objects\TCPage;

// Tin Can Forum version.
define('TC_VERSION', '1.0.3');

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
$page = null;

$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
$path = '/';
if (!is_null($request_uri)) {
    $path = explode('?', $request_uri)[0];
}

$page_template = null;

// Get page template if available, otherwise default to 404.
if (('/' == $path) && empty($page_id)) {
    $page_template = 'front';
}

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
TCTemplate::render('page/'.$page_template, $settings['theme'], ['page' => $page, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('footer', $settings['theme'], ['page' => $page, 'settings' => $settings, 'user' => $user]);
