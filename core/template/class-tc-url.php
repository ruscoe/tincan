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
   * TODO.
   *
   * @since 0.06
   */
  public static function create_url($page, $params = [], $pretty = false)
  {
    $url = '';
    if ($pretty) {
      // TODO: Pretty URL formatting.
    } else {
      $url = self::create_standard_url($page, $params);
    }

    return $url;
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
}
