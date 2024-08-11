<?php

namespace TinCan\content;

/**
 * Post content parser.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.03
 */
class TCPostParser
{
    /**
     * Converts post content from text to HTML.
     *
     * @since 0.03
     *
     * @param string $content the post content
     *
     * @return string the post content as HTML
     */
    public function get_html($content)
    {
        $parsed_content = $this->parse_lines($content);

        return $parsed_content;
    }

    /**
     * Converts new lines to HTML linebreaks.
     *
     * @since 0.03
     *
     * @param string $content the content to parse
     *
     * @return string the parsed content
     */
    private function parse_lines($content)
    {
        return nl2br($content);
    }
}
