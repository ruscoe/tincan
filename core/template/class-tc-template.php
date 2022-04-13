<?php
/**
 * Template parser.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCTemplate
{
    public static function render($template_name, $data)
    {
        try {
            include TC_BASE_PATH . '/templates/' . $template_name . '.php';
        } catch (Exception $e) {
            // TODO: Handle this exception.
        }
    }
}
