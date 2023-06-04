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
     * Creates either a regular parameterized URL or friendly formatted URL.
     *
     * @param mixed  $page     the page identifier
     *                         int if using the page ID
     *                         string if using the base path
     * @param array  $params   array of parameters to append to the URL
     * @param bool   $friendly true if creating a friendly formatted URL
     * @param string $slug     object-specific slug used for friendly formatted URL
     *
     * @return string the URL
     *
     * @since 0.08
     */
    public static function create_url($page, $params = [], $friendly = false, $slug = null)
    {
        $url = '/';

        if ($friendly && !empty($page)) {
            $url .= str_replace('%slug%', $slug, $page).'/';

            // The first parameter here must be ?.
            // All subsequent parameters are prefixed with &.
            if (!empty($params)) {
                $url .= '?';

                foreach ($params as $name => $value) {
                    if ('?' !== substr($url, -1)) {
                        $url .= '&';
                    }
                    $url .= $name.'='.urlencode($value);
                }
            }
        } elseif (!empty($page)) {
            $url .= 'index.php?page='.$page;

            foreach ($params as $name => $value) {
                $url .= '&'.$name.'='.urlencode($value);
            }
        }

        return $url;
    }

    /**
     * Legacy function.
     *
     * @since 0.06
     */
    public static function create_standard_url($page, $params)
    {
        throw new TCException('Function deprecated. Use TCURL::create_url instead.');
    }

    /**
     * Legacy function.
     *
     * @since 0.08
     */
    public static function create_friendly_url($base, TCObject $object, $params = [])
    {
        throw new TCException('Function deprecated. Use TCURL::create_url instead.');
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
