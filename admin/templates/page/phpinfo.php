<?php

/**
 * Page template to show phpinfo.
 *
 * @since 0.05
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page = $data['page'];
?>

<h1><?php echo $page->page_title; ?></h1>

<?php

phpinfo();
