<?php

namespace TinCan;

/**
 * Template parser.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCTemplate
{
  public static function render($template_name, $theme, $data)
  {
    try {
      include TC_BASE_PATH.'/themes/'.$theme.'/templates/'.$template_name.'.php';
    } catch (Exception $e) {
      // TODO: Handle this exception.
    }
  }
}
