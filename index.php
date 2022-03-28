<?php

require 'tc-config.php';

require 'includes/include-db.php';
require 'includes/include-objects.php';
require 'includes/include-template.php';

// Test posts data.
$posts = array(
  (object) array(
    'id' => 1,
    'title' => 'Aut velit non ea perspiciatis ex',
    'url' => '/posts/1'
  )
);

foreach ($posts as $post) {

  $data = array('post' => $post);
  TCTemplate::render('post-preview', $data);

}
