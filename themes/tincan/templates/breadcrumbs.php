<?php

use TinCan\TCData;

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
while (null !== $object->get_parent()) {
  $parent = $object->get_parent();
  if (null !== $parent) {
    $object = $db->load_object($parent, $parent->get_primary_key_value());
    $chain[] = $object;
  }
}

// Map object primary keys to the pages the objects appear on.
// This is used to create the breadcrumb links.
$template_page_map = [
  'board_id' => ('/?page='.$settings['page_board'].'&board='),
  'board_group_id' => ('/?page='.$settings['page_board_group'].'&board_group='),
];

if (!empty($chain)) {
  ?>
  <ul class="breadcrumbs">
    <li class="home"><a href="/">Home</a></li>
<?php
  $chain_length = count($chain);

  $object = null;

  for ($i = $chain_length; $i > 0; --$i) {
    $object = $chain[$i - 1];
    $object_id = $parent->get_primary_key_value();
    $primary_key = $object->get_primary_key();
    $page_url = (isset($template_page_map[$primary_key])) ? $template_page_map[$primary_key] : null;

    if (!empty($page_url)) {
      $page_url .= $object->get_primary_key_value();

      echo '<li class="subpage"><a href="'.$page_url.'">'.$object->get_name().'</a></li>';
    }
  } ?>
  </ul>
<?php
}
