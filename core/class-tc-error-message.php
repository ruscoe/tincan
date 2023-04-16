<?php

namespace TinCan;

/**
 * Error message provider.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.10
 */
class TCErrorMessage
{
    /**
     * Gets an error message from an error code depending on the context used.
     *
     * @since 0.10
     *
     * @param string $context    the context of the error message (usually a form)
     * @param string $error_code the code used to identify the error
     *
     * @return string the appropriate error message
     */
    public function get_error_message($context, $error_code)
    {
        if ('log-in' == $context) {
            // User is logging in.
            if (TCObject::ERR_NOT_FOUND == $error_code) {
                $error_text = 'No match found for the credentials you\'re logging in with.';
            }
        } elseif ('create-account' == $context) {
            // User is creating an account.
            if (TCUser::ERR_USER == $error_code) {
                $error_text = 'Please choose a username at least '.TCUser::MIN_USERNAME_LENGTH.' characters long. Alphanumeric characters only.';
            } elseif (TCUser::ERR_EMAIL == $error_code) {
                $error_text = 'Please check your email address has been entered correctly.';
            } elseif (TCUser::ERR_PASSWORD == $error_code) {
                $error_text = 'Please choose a password at least '.TCUser::MIN_PASSWORD_LENGTH.' characters long.';
            } elseif (TCUser::ERR_USERNAME_EXISTS == $error_code) {
                $error_text = 'The username you entered has been taken. Please choose another.';
            } elseif (TCUser::ERR_EMAIL_EXISTS == $error_code) {
                $error_text = 'The email address you entered is in use. You may already have an account.';
            }
        } elseif ('reset-password' == $context) {
            // User is attempting to reset their password.
            if (TCUser::ERR_NOT_FOUND == $error_code) {
                $error_text = 'Email address not found. You may have signed up with a different address.';
            }
        } elseif ('thread' == $context) {
            // User is posting a reply to a thread.
            if (TCUser::ERR_NOT_AUTHORIZED == $error_code) {
                $error_text = 'Your account isn\'t able to post in this thread.';
            } elseif (TCObject::ERR_NOT_SAVED == $error_code) {
                $error_text = 'Couldn\'t reply to this thread right now. Please try again later.';
            }
        } elseif ('new-thread' == $context) {
            // User is posting a reply to a thread.
            if (TCUser::ERR_NOT_AUTHORIZED == $error_code) {
                $error_text = 'Your account isn\'t able to create a new thread.';
            } elseif (TCThread::ERR_TITLE == $error_code) {
                $error_text = 'Please use a longer thread title.';
            } elseif (TCObject::ERR_NOT_SAVED == $error_code) {
                $error_text = 'Couldn\'t create a thread right now. Please try again later.';
            }
        }

        if (empty($error_text)) {
            $error_text = 'A general error has occurred. Please try again later. ('.$context.' : '.$error_code.')';
        }

        return $error_text;
    }
}
