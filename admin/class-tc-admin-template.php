<?php

namespace TinCan\Admin;

/**
 * Template parser.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCAdminTemplate
{
    public static function render($template_name, $data)
    {
        try {
            include TC_BASE_PATH.'/admin/templates/'.$template_name.'.php';
        } catch (Exception $e) {
        }
    }
}
