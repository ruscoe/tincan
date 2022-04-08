<?php
  $page = $data['page'];

  $field_names = array('username', 'password');

  $errors = array();

  // If there are any URL parameters matching field names then the user has
  // been returned to this form due to a submission error.
  // Collect errors here.
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
  <label for="username">Username</label>
  <input type="text" name="username" />

  <label for="password">Password</label>
  <input type="password" name="password" />

  <input type="hidden" name="ajax" value="" />
  <input type="submit" name="log_in" value="Log in" />
</form>
