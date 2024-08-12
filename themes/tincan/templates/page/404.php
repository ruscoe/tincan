<?php

use TinCan\template\TCTemplate;
use TinCan\template\TCURL;

/**
 * 404 page template.
 *
 * @since 0.08
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$settings = $data['settings'];
$page = $data['page'];
$user = $data['user'];

TCTemplate::render('header', $settings['theme'], ['page_title' => $page->page_title, 'page_template' => $page->template, 'settings' => $settings, 'user' => $user]);
TCTemplate::render('breadcrumbs', $settings['theme'], ['object' => null, 'settings' => $settings]);
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Sorry! The page you're looking for couldn't be found.</p>

<p><a href="<?php echo TCURL::create_url(null); ?>">Return to forum</a></p>
