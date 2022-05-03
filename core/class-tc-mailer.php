<?php

namespace TinCan;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Tin Can mail handler.
 *
 * @since 0.07
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCMailer
{
  /**
   * @since 0.07
   */
  private PHPMailer $mailer;

  public function __construct($enable_exceptions = true)
  {
    $this->mailer = new PHPMailer($enable_exceptions);

    // Enable SMTP.
    $this->mailer->isSMTP();
    $this->mailer->SMTPAuth = true;

    if (TC_SMTP_DEBUG) {
      $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
    }

    if (TC_SMTP_TLS) {
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    $this->mailer->Host = TC_SMTP_HOST;
    $this->mailer->Username = TC_SMTP_USER;
    $this->mailer->Password = TC_SMTP_PASS;
    $this->mailer->Port = TC_SMTP_PORT;
  }

  /**
   * Sends an email.
   *
   * TODO: Add email templates.
   *
   * @since 0.07
   *
   * @param array $recipients associative array of names and email addresses
   *   [
   *     'name' => 'Oscar Wilde',
   *     'email' => 'happyprince@example.org'
   *   ]
   *
   * @return bool true if email was successfully sent
   */
  public function send_mail($from_name, $from_email, $recipients)
  {
    $this->mailer->setFrom($from_email, $from_name);

    foreach ($recipients as $recipient) {
      $this->mailer->addAddress($recipient['email'], $recipient['name']);
    }

    $this->mailer->isHTML(false);
    $this->mailer->Subject = 'Tin Can Forum Test Email';
    $this->mailer->Body = 'Hello! Here is an email from Tin Can Forum!';

    $this->mailer->send();

    return true;
  }
}
