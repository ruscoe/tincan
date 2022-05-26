<?php

namespace TinCan;

/**
 * URL formatting functionality.
 *
 * @since 0.06
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCURL
{
  /**
   * Legacy function.
   *
   * @since 0.08
   */
  public static function create_url($page, $params = [])
  {
    return TCURL::create_standard_url($page, $params);
  }

  /**
   * TODO.
   *
   * @since 0.06
   */
  public static function create_standard_url($page, $params)
  {
    $url = '/index.php';

    if (!empty($page)) {
      $url .= '?page='.$page;

      // URL parameters only used when a page is specified.
      foreach ($params as $name => $value) {
        $url .= '&'.$name.'='.urlencode($value);
      }
    }

    return $url;
  }

  /**
   * TODO.
   *
   * @since 0.08
   */
  public static function create_friendly_url($base, TCObject $object, $params = [])
  {
    // $url = $base.'/'.$object->slug.'.'.$object->get_primary_key_value();
    $url = '/'.$base.'/'.$object->get_slug();

    return $url;
  }

  /**
   * TODO.
   *
   * @since 0.09
   */
  public static function get_installer_url()
  {
    return '/install.php';
  }
}
