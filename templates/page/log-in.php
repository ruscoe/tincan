<?php
/**
 * Log in page template.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

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

<form id="log-in" action="/actions/log-in.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input class="text-input" type="text" name="username" />
    </div>
  </div>

  <div class="fieldset">
    <label for="password">Password</label>
    <div class="field">
      <input class="text-input" type="password" name="password" />
    </div>
  </div>

  <input type="hidden" name="ajax" value="" />

  <div class="fieldset button">
    <input type="submit" name="log_in" value="Log in" />
  </div>
</form>
