<?php

use TinCan\TCURL;

/**
 * 404 page template.
 *
 * @since 0.08
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<p>Sorry! The page you're looking for couldn't be found.</p>

<p><a href="<?php echo TCURL::create_url(null); ?>">Return to forum</a></p>
