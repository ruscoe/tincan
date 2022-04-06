<?php
  $page = $data['page'];

  $field_names = array('username', 'password');

  $errors = array();

  foreach ($field_names as $name) {
    if (isset($_GET[$name])) {
      $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
  }
?>

<h1><?=$page->page_title?></h1>

<?php
  if (!empty($errors)) {
    TCTemplate::render('form-errors', array('errors' => array_values($errors)));
  }
?>

<form action="/actions/log-in.php" method="POST">
  <input type="text" name="username" />
  <input type="password" name="password" />
  <input type="hidden" name="ajax" value="" />
  <input type="submit" name="submit_thread" value="Log in" />
</form>
