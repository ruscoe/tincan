<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCPage;
use TinCan\TCTemplate;
use TinCan\TCURL;
use TinCan\TCUserSession;

/**
 * Forum entry point.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

// Base configuation.
require 'tc-config.php';
// Composer autoload.
require TC_BASE_PATH.'/vendor/autoload.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-mailer.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-content.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  // echo $e->getMessage();
  // For now assume this means the site hasn't been installed.
  // Redirect to the installer.
  header('Location: '.TCURL::get_installer_url());
  exit;
}

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page_slug = null;
$page = null;

if (empty($page_id)) {
  // Work out page ID from friendly URL.
  $path = filter_input(INPUT_SERVER, 'REQUEST_URI');

  $base_urls_to_page_ids = [
    $settings['base_url_board_groups'] => $settings['page_board_group'],
    $settings['base_url_boards'] => $settings['page_board'],
    $settings['base_url_threads'] => $settings['page_thread'],
    $settings['base_url_users'] => $settings['page_user'],
    'log-in' => $settings['page_log_in'],
    'log-out' => $settings['page_log_out'],
    'create-account' => $settings['page_create_account'],
    'reset-password' => $settings['page_reset_password'],
  ];

  // Attempt to match a base URL with the current path.
  foreach ($base_urls_to_page_ids as $base_url => $base_page_id) {
    // Start regex with initial slash.
    $regex_prefix = '#^/';
    // End regex with optional trailing slash. Case insensitive.
    $regex_suffix = '/?$#i';
    // Replace the %slug% token with the regex string.
    $path_regex = $regex_prefix . str_replace('%slug%', '([a-z_\-0-9]*)', $base_url) . $regex_suffix;

    $page_matches = null;
    preg_match($path_regex, $path, $page_matches);

    // We have a match! Set the page ID and slug to be used by the template.
    if (!empty($page_matches)) {
      $page_id = $base_page_id;
      $page_slug = (isset($page_matches[1])) ? $page_matches[1] : null;
    }
  }
}

// Get page template if available, otherwise default to front page.
if (!empty($page_id)) {
  $page = $db->load_object(new TCPage(), $page_id);

  if (!empty($page)) {
    $page_template = $page->template;
  } else {
    // Page not found, redirect to 404 error page.
    header('Location: '.TCURL::create_url($settings['page_404']));
    exit;
  }
} else {
  $page_template = 'front';
}

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Render page.
TCTemplate::render('header', $settings['theme'], ['page_template' => $page_template, 'settings' => $settings, 'user' => $user]);

TCTemplate::render('page/'.$page_template, $settings['theme'], ['page' => $page, 'settings' => $settings, 'user' => $user, 'slug' => $page_slug]);

TCTemplate::render('footer', $settings['theme'], null);
