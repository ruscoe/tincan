<?php

namespace TinCan\controllers;

use TinCan\TCException;
use TinCan\TCMailer;
use TinCan\controllers\TCController;
use TinCan\objects\TCMailTemplate;
use TinCan\objects\TCObject;

/**
 * Mail controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCMailController extends TCController
{
    /**
     * Updates a mail template.
     *
     * @param int    $mail_template_id   The mail template ID.
     * @param string $mail_template_name The mail template name.
     * @param string $content            The mail template content.
     *
     * @return bool TRUE if the mail template was updated, otherwise FALSE.
     *
     * @since 0.16
     */
    public function edit_mail_template($mail_template_id, $mail_template_name, $content)
    {
        $mail_template = $this->db->load_object(new TCMailTemplate(), $mail_template_id);

        if (empty($mail_template)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $mail_template->mail_template_name = $mail_template_name;
        $mail_template->content = $content;

        try {
            $saved_mail_template = $this->db->save_object($mail_template);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    public function send_test_mail($recipient)
    {
        $settings = $this->db->load_settings();

        $mailer = new TCMailer($settings);

        try {
            $mailer->send_mail(
                $settings['site_email_name'],
                $settings['site_email_address'],
                'Test Email From Tin Can Forum',
                'This is a test.',
                [['name' => $recipient, 'email' => $recipient]],
            );
        } catch (TCException $e) {
            $this->error = TCMailer::ERR_SMTP;
            return false;
        }

        return true;
    }
}
