<?php

$thread_id = filter_input(INPUT_GET, 'thread', FILTER_VALIDATE_INT);

$db = new TCData();

$thread = $db->load_object(new TCThread(), $thread_id);

?>

<h1><?=$thread->thread_title?></h1>

<?php

$conditions = array(
  array(
    'field' => 'thread_id',
    'value' => $thread_id
  )
);

// TODO: Sorting and pagination.
$posts = $db->load_objects(new TCPost(), array(), $conditions);

foreach ($posts as $post) {
  TCTemplate::render('post', array('post' => $post));
}
