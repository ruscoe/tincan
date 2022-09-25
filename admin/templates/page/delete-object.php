<?php

use TinCan\TCBoard;
use TinCan\TCBoardGroup;
use TinCan\TCData;
use TinCan\TCMailTemplate;
use TinCan\TCPage;
use TinCan\TCThread;
use TinCan\TCUser;

/**
 * Generic page template for admin object deletion.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$object_type = filter_input(INPUT_GET, 'object_type', FILTER_SANITIZE_STRING);
$object_id = filter_input(INPUT_GET, 'object_id', FILTER_SANITIZE_NUMBER_INT);

$db = new TCData();
$settings = $db->load_settings();

$class = null;
$object = null;

switch ($object_type) {
    case 'board_group':
        $class = new TCBoardGroup();
        break;
    case 'board':
        $class = new TCBoard();
        break;
    case 'page':
        $class = new TCPage();
        break;
    case 'thread':
        $class = new TCThread();
        break;
    case 'user':
        $class = new TCUser();
        break;
    case 'mail_template':
        $class = new TCMailTemplate();
        break;
}

if (!empty($class)) {
    $object = $db->load_object($class, $object_id); ?>

<h1>Really delete <?php echo $object->get_name(); ?>?</h1>

<form id="delete-object" action="/admin/actions/delete-object.php" method="POST">
  <input type="hidden" name="object_type" value="<?php echo $object_type; ?>" />
  <input type="hidden" name="object_id" value="<?php echo $object_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Delete" />
  </div>
</form>
<?php
} else {
    ?>
  <h1>Unknown object type</h1>
  <p>"<?php echo $object_type; ?>" isn't a known object type and cannot be deleted.</p>
  <?php
}
?>
