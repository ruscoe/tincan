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
  echo $e->getMessage();
  exit;
}

$page_id = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page_slug = null;
$page = null;

if (empty($page_id)) {
  // Work out page ID from friendly URL.
  $path_parts = explode('/', $_SERVER['REQUEST_URI']);

  $page_slug = isset($path_parts[2]) ? trim($path_parts[2]) : null;

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

  foreach ($base_urls_to_page_ids as $base_url => $base_page_id) {
    if ($path_parts[1] == $base_url) {
      $page_id = $base_page_id;
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
