<?php
/**
 * Page template for thread editing.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];

$object_id = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?=$page->page_title?></h1>

<?php

$db = new TCData();

$object = $db->load_object(new TCThread(), $object_id);
?>

<form action="/admin/actions/update-object.php" method="POST">
  <label for="thread_title">Thread Title</label>
  <input type="text" name="thread_title" value="<?=$object->thread_title?>" />
  <input type="hidden" name="object_type" value="thread" />
  <input type="hidden" name="object_id" value="<?=$object->thread_id?>" />
  <input type="submit" value="Update Thread" />
</form>
