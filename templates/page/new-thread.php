<?php
  $page = $data['page'];

  $field_names = array('thread_title', 'thread_post');

  $errors = array();

  // If there are any URL parameters matching field names then the user has
  // been returned to this form due to a submission error.
  // Collect errors here.
  foreach ($field_names as $name) {
    if (isset($_GET[$name])) {
      $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
  }

  $board_id = filter_input(INPUT_GET, 'board', FILTER_SANITIZE_NUMBER_INT);

  $db = new TCData();
  $settings = $db->load_settings();

  // Get logged in user.
  $session = new TCUserSession();
  $session->start_session();
  $user_id = $session->get_user_id();
  $user = (!empty($user_id)) ? $db->load_user($user_id) : null;

  // Check user has permission to create a new thread.
  if (empty($user) || !$user->can_perform_action(TCUser::ACT_CREATE_THREAD)) {
?>

  <div>
    Please <a href="/?page=<?=$settings['page_log_in']?>">log in</a>
    or <a href="/?page=<?=$settings['page_create_account']?>">create an account</a> if you'd like to do that!
  </div>

<?php
  } else {
    $board = $db->load_object(new TCBoard(), $board_id);
?>

  <h1><?=$page->page_title?></h1>

  <?php
    if (!empty($errors)) {
      TCTemplate::render('form-errors', array('errors' => array_values($errors)));
    }
  ?>

  <form action="/actions/create-thread.php" method="POST">
    <label for="thread_title">Thread Title</label>
    <input type="text" name="thread_title" />

    <label for="post_content">Thread Content</label>
    <textarea name="post_content" rows="20" cols="30"></textarea>

    <input type="hidden" name="board_id" value="<?=$board->board_id?>" />
    <input type="hidden" name="ajax" value="" />
    <input type="submit" name="submit_thread" value="Submit thread" />
  </form>

<?php
  }
?>
