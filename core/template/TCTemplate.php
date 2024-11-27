<?php

namespace TinCan\template;

/**
 * Template parser.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCTemplate
{
    /**
     * Includes and renders a template file. Will use parent theme if available.
     *
     * @see /themes/README.md
     *
     * @param string $template_name the name of the template to render
     * @param string $theme         the theme containing the template
     * @param array  $data          optional array of data referenced by the template
     *
     * @since 0.01
     */
    public static function render($template_name, $theme, $data)
    {
        $parent_theme = self::get_parent_theme($theme);

        $template_path = getenv('TC_BASE_PATH').'/themes/'.$theme.'/templates/'.$template_name.'.php';

        if (!file_exists($template_path) && (null !== $parent_theme)) {
            // Template not found; try the parent theme.
            $template_path = getenv('TC_BASE_PATH').'/themes/'.$parent_theme.'/templates/'.$template_name.'.php';
        }

        if (file_exists($template_path)) {
            if ($data['settings']['enable_theme_debug'] ?? false) {
                echo self::generateTemplateDebug($template_path, $template_name);
            }

            include $template_path;
        } else {
            // Output an error if the template is missing.
            // Use relative path so full paths aren't exposed to users.
            echo 'Unable to render missing template: /themes/'.$theme.'/templates/'.$template_name.'.php';
        }
    }

    /**
     * Attempts to determine a parent theme from the name of a given theme.
     *
     * @param string $theme the name of the theme to check for a parent
     *
     * @return string the name of the parent theme, if it exists
     *
     * @since 0.11
     */
    public static function get_parent_theme($theme)
    {
        $theme_parts = explode('-', $theme);

        return (count($theme_parts) > 1) ? $theme_parts[0] : null;
    }

    /**
     * Output a block of HTML comments with details on the template file.
     *
     * @param string $template_path the server file path to the template
     * @param string $template_name the name of the template to render
     *
     * @return string HTML commment containing the name and path of the template.
     */
    protected static function generateTemplateDebug($template_path, $template_name)
    {
        // Get length of the longest line will be the template path.
        // 12 is the length of prefix and suffix of which the path is added: "-- Path:  --"
        $dashcount = strlen($template_path) + 12;

        // Get number of dashes required either side of the template key.
        // -12 adjusts for the length of prefix and suffix to which the name is added: " Template:  "
        $namedashes = $dashcount - strlen($template_name) - 12;

        $output = [
            "<!" . str_repeat('-', $dashcount) . ">",
            "<!" . str_repeat('-', floor($namedashes / 2) + $namedashes % 2) . " Template: $template_name " . str_repeat('-', floor($namedashes / 2)) . ">",
            "<!-- Path: $template_path -->",
            "<!" . str_repeat('-', $dashcount) . ">\n\n",
        ];

        return implode(PHP_EOL, $output);
    }
}
