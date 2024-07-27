<?php

use TinCan\db\TCData;
use TinCan\objects\TCBoard;
use TinCan\objects\TCObject;
use TinCan\objects\TCThread;
use TinCan\objects\TCPost;
use TinCan\template\TCTemplate;

/**
 * Page template for thread editing.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$user = $data['user'];

$thread_id = filter_input(INPUT_GET, 'thread_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
?>

<h1>Edit Thread</h1>

<?php

// Error handling.
if (!empty($error)) {
  switch ($error) {
      case TCObject::ERR_NOT_FOUND:
          $error_msg = 'Thread not found.';
          break;
      case TCObject::ERR_NOT_SAVED:
          $error_msg = 'Thread could not be updated.';
          break;
      default:
          $error_msg = $error;
  }

  TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

$db = new TCData();
$settings = $db->load_settings();

$thread = (!empty($thread_id)) ? $db->load_object(new TCThread(), $thread_id) : new TCThread();

// Get available boards.
$boards = $db->load_objects(new TCBoard());
?>

<?php if (empty($boards)) { ?>
  <p>You'll need to <a href="/admin/index.php?page=<?php echo $settings['admin_page_boards']; ?>" title="Boards">create a board</a> first.</p>
<?php } else { ?>

<form id="edit-thread" action="/admin/actions/update-thread.php" method="POST">
  <div class="fieldset">
    <label for="thread_title">Thread Title</label>
    <div class="field">
      <input class="text-input" type="text" name="thread_title" value="<?php echo $thread->thread_title; ?>" />
    </div>
  </div>

  <div class="fieldset">
    <label for="board_id">Board</label>
    <div class="field">
      <select name="board_id">
        <?php
        foreach ($boards as $board) {
            $selected = ($board->board_id == $thread->board_id) ? ' selected' : '';
            echo "<option value=\"{$board->board_id}\"{$selected}>{$board->board_name}</option>\n";
        }
    ?>
      </select>
    </div>
  </div>

    <?php
    $first_post = $db->load_object(new TCPost(), $thread->first_post_id);
    ?>

  <div class="fieldset textarea">
    <label for="content">Content</label>
    <div class="field">
      <textarea name="content" rows="20" cols="30"><?php echo (!empty($first_post)) ? $first_post->content : ''; ?></textarea>
    </div>
  </div>

  <input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />
  <input type="hidden" name="created_by_user" value="<?php echo $user->user_id; ?>" />
  <input type="hidden" name="updated_by_user" value="<?php echo $user->user_id; ?>" />

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="<?php echo (!empty($thread_id)) ? 'Update Thread' : 'Add Thread'; ?>" />
  </div>
</form>

<?php } ?>
