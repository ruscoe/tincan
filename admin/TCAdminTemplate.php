<?php

namespace TinCan\Admin;

/**
 * Template parser.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCAdminTemplate
{
    public static function render($template_name, $data)
    {
        try {
            include getenv('TC_BASE_PATH').'/admin/templates/'.$template_name.'.php';
        } catch (\Exception $e) {
        }
    }
}
