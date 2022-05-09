<?php

use TinCan\TCData;
use TinCan\TCException;
use TinCan\TCJSONResponse;
use TinCan\TCMailer;
use TinCan\TCMailTemplate;
use TinCan\TCURL;
use TinCan\TCUser;
use TinCan\TCUserSession;

/**
 * Tin Can password reset handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
require '../tc-config.php';
// Composer autoload.
require TC_BASE_PATH.'/vendor/autoload.php';

require TC_BASE_PATH.'/core/class-tc-exception.php';
require TC_BASE_PATH.'/core/class-tc-mailer.php';
require TC_BASE_PATH.'/includes/include-db.php';
require TC_BASE_PATH.'/includes/include-objects.php';
require TC_BASE_PATH.'/includes/include-template.php';
require TC_BASE_PATH.'/includes/include-user.php';

require 'class-tc-json-response.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$ajax = filter_input(INPUT_POST, 'ajax', FILTER_SANITIZE_STRING);

$db = new TCData();

try {
  $settings = $db->load_settings();
} catch (TCException $e) {
  echo $e->getMessage();
  exit;
}

$error = null;

$user = null;

$error = (empty($email)) ? TCUser::ERR_NOT_FOUND : null;

if (empty($error)) {
  // Find user with matching email.
  $conditions = [
    [
      'field' => 'email',
      'value' => $email,
    ],
  ];

  try {
    $user_results = $db->load_objects(new TCUser(), [], $conditions);
    if (!empty($user_results)) {
      $user = reset($user_results);
    }
  } catch (TCException $e) {
    echo $e->getMessage();
    exit;
  }
}

if (empty($user)) {
  $error = TCUser::ERR_NOT_FOUND;
}

if (empty($error)) {
  $reset_code = $user->generate_password_reset_code();

  $user->password_reset_code = $reset_code;
  $db->save_object($user);
  // TODO: Ensure code is created and saved.
}

if (empty($error)) {
  $reset_url = $settings['base_url'].TCURL::create_url($settings['page_set_password'], ['code' => $user->password_reset_code]);

  // Send password reset code to the user.
  $mailer = new TCMailer();

  // Load email template.
  // TODO: Error handling.
  $mail_template = $db->load_object(new TCMailTemplate(), $settings['mail_reset_password']);
  $mail_subject = $mail_template->mail_template_name;
  $mail_content = $mailer->tokenize_template($mail_template, ['url' => $reset_url]);

  $recipients = [
    ['name' => $user->username, 'email' => $user->email],
  ];

  $mailer->send_mail($settings['site_email_name'],
    $settings['site_email_address'],
    $mail_subject,
    $mail_content,
    $recipients);
}

if (!empty($ajax)) {
  $response = new TCJSONResponse();

  $response->success = (empty($error));
  $response->errors = [$error];

  exit($response->get_output());
} else {
  $destination = '';

  if (empty($error)) {
    // Send user to the reset password page with a success message.
    $destination = TCURL::create_url($settings['page_reset_password'], ['status' => 'sent']);
  } else {
    // Send user back to the reset password page with an error.
    $destination = TCURL::create_url($settings['page_reset_password'], ['error' => $error]);
  }

  header('Location: '.$destination);
  exit;
}
