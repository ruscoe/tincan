<?php
/**
 * Template parser.
 *
 * @package Tin Can
 * @since 0.01
 */

class TCAdminTemplate {

  static function render($template_name, $data) {

    try {
      include TC_BASE_PATH . '/admin/templates/' . $template_name . '.php';
    }
    catch (Exception $e) {

    }
  }

}
