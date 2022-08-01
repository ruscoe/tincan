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
    $template_path = TC_BASE_PATH.'/themes/'.$theme.'/templates/'.$template_name.'.php';

    if (file_exists($template_path)) {
      include $template_path;
    } else {
      // Output an error if the template is missing.
      // Use relative path so full paths aren't exposed to users.
      echo 'Unable to render missing template: /themes/'.$theme.'/templates/'.$template_name.'.php';
    }
  }
}
