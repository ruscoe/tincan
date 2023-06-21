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
        $parsed_content = $this->parse_text($parsed_content);
        $parsed_content = $this->parse_images($parsed_content);
        $parsed_content = $this->parse_links($parsed_content);

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

    /**
     * Converts text tags to HTML.
     *
     * @since 0.03
     *
     * @param string $content the content to parse
     *
     * @return string the parsed content
     */
    private function parse_text($content)
    {
        $parsed_content = $content;

        $replacements = $this->get_tag_replacements();

        foreach ($replacements as $tag => $html) {
            $tag = preg_quote($tag);
            $replaced_content = preg_replace("/{$tag}(.*){$tag}/", "<{$html}>$1</{$html}>", $parsed_content);

            if (!empty($replaced_content)) {
                $parsed_content = $replaced_content;
            }
        }

        return $parsed_content;
    }

    private function parse_images($content)
    {
        $parsed_content = preg_replace("/!\[(.*)\]\((.*)\)/", "<img src=\"$2\" alt=\"$1\" />", $content);

        return $parsed_content;
    }

    private function parse_links($content)
    {
        $parsed_content = preg_replace("/\[(.*)\]\((.*)\)/", "<a href=\"$2\">$1</a>", $content);

        return $parsed_content;
    }

    /**
     * Returns tags and their HTML replacements.
     *
     * @return array associative array of tags and their HTML replacements
     */
    private function get_tag_replacements()
    {
        $replacements = [
              '=====' => 'h5',
              '====' => 'h4',
              '===' => 'h3',
              '==' => 'h2',
              '=' => 'h1',
              '**' => 'b',
              '*' => 'i',
              '__' => 'u',
            ];

        return $replacements;
    }
}
