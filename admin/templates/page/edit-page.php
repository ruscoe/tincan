<?php

use TinCan\TCData;
use TinCan\TCPage;

/**
 * Page template for page editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$page_id = filter_input(INPUT_GET, 'page_id', FILTER_SANITIZE_NUMBER_INT);
?>

<h1><?php echo (!empty($page_id)) ? 'Edit Page' : 'Add New Page'; ?></h1>

<?php

$db = new TCData();

$page = (!empty($page_id)) ? $db->load_object(new TCPage(), $page_id) : new TCPage();

$form_action = (!empty($page_id)) ? '/admin/actions/update-page.php' : '/admin/actions/create-page.php';
?>

<form id="edit-page" action="<?php echo $form_action; ?>" method="POST">
  <div class="fieldset">
    <label for="page_title">Page Title</label>
    <div class="field">
      <input type="text" name="page_title" value="<?php echo $page->page_title; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="page_title">Page Template</label>
    <div class="field">
      <input type="text" name="template" value="<?php echo $page->template; ?>" />
    </div>
  </div>

  <input type="hidden" name="page_id" value="<?php echo $page->page_id; ?>" />

  <div class="fieldset button">
    <input type="submit" value="<?php echo (!empty($page_id)) ? 'Update Page' : 'Add Page'; ?>" />
  </div>
</form>
