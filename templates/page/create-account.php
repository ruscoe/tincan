<?php
/**
 * Create account page template.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
  $page = $data['page'];

  $field_names = ['username', 'email', 'password'];

  $errors = [];

  foreach ($field_names as $name) {
    if (isset($_GET[$name])) {
      $errors[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
  }
?>

<h1 class="section-header"><?php echo $page->page_title; ?></h1>

<?php
  if (!empty($errors)) {
    TCTemplate::render('form-errors', ['errors' => array_values($errors)]);
  }
?>

<form id="create-account" action="/actions/create-account.php" method="POST">
  <div class="fieldset">
    <label for="username">Username</label>
    <div class="field">
      <input class="text-input" type="text" name="username" />
    </div>
  </div>

  <div class="fieldset">
    <label for="email">Email address</label>
    <div class="field">
      <input class="text-input" type="text" name="email" />
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
    <input type="submit" name="submit_thread" value="Create account" />
  </div>
</form>
