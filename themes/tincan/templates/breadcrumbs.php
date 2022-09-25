<?php

use TinCan\TCData;
use TinCan\TCURL;

/**
 * Breadcrumb template.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$object = $data['object'];
$settings = $data['settings'];

$db = new TCData();

$chain = [];

// Follow the chain of parent objects until no more exist.
if (!empty($object)) {
    while (null !== $object->get_parent()) {
        $parent = $object->get_parent();
        if (null !== $parent) {
            $object = $db->load_object($parent, $parent->get_primary_key_value());
            $chain[] = $object;
        }
    }
}

// Map object primary keys to the pages the objects appear on.
// This is used to create the breadcrumb links.
$template_page_map = [
  'board_id' => [$settings['base_url_boards'], $settings['page_board'], 'board'],
  'board_group_id' => [$settings['base_url_board_groups'], $settings['page_board_group'], 'board_group'],
];
?>

<ul class="breadcrumbs">
  <li class="home"><a href="/">Home</a></li>

<?php
if (!empty($chain)) {
    $chain_length = count($chain);

    $object = null;

    for ($i = $chain_length; $i > 0; --$i) {
        $object = $chain[$i - 1];
        $object_id = $parent->get_primary_key_value();
        $primary_key = $object->get_primary_key();

        $page_url = null;

        if (isset($template_page_map[$primary_key])) {
            $url_id = ($settings['enable_urls']) ? $template_page_map[$primary_key][0] : $template_page_map[$primary_key][1];
            $object_name = $template_page_map[$primary_key][2];
            $page_url = TCURL::create_url($url_id, [$object_name => $object_id], $settings['enable_urls'], $object->get_slug());
        }

        if (!empty($page_url)) {
            echo '<li class="subpage"><a href="'.$page_url.'">'.$object->get_name().'</a></li>';
        }
    }
}
?>

</ul>

<?php
