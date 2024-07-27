<?php

namespace TinCan\template;

use TinCan\TCException;
use TinCan\objects\TCObject;

/**
 * URL formatting functionality.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.06
 */
class TCURL
{
    /**
     * Creates a parameterized URL.
     *
     * @param mixed  $page     the page identifier
     *                         int if using the page ID
     *                         string if using the base path
     * @param array  $params   array of parameters to append to the URL
     *
     * @return string the URL
     *
     * @since 0.08
     */
    public static function create_url($page, $params = [])
    {
        $url = '/';

        if (!empty($page)) {
            $url .= 'index.php?page='.$page;

            foreach ($params as $name => $value) {
                $url .= '&'.$name.'='.urlencode($value);
            }
        }

        return $url;
    }

    /**
         * Creates a parameterized URL for the admin section.
         *
         * @param mixed  $page     the page identifier
         *                         int if using the page ID
         *                         string if using the base path
         * @param array  $params   array of parameters to append to the URL
         *
         * @return string the URL
         *
         * @since 0.16
         */
    public static function create_admin_url($page, $params = [])
    {
        $url = '/admin/';

        if (!empty($page)) {
            $url .= 'index.php?page='.$page;

            foreach ($params as $name => $value) {
                $url .= '&'.$name.'='.urlencode($value);
            }
        }

        return $url;
    }

    /**
     * Gets the URL of the Tin Can forum installer.
     *
     * @since 0.09
     */
    public static function get_installer_url()
    {
        return '/install.php';
    }
}
