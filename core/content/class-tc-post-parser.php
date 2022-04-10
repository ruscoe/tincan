<?php
/**
 * Post content parser.
 *
 * @package Tin Can
 * @since 0.03
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCPostParser
{
    public const TAG_HEADER_1 = 'H1';
    public const TAG_HEADER_2 = 'H2';
    public const TAG_HEADER_3 = 'H3';

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
            $replaced_content = preg_replace("/\[{$tag}\](.*)\[\/{$tag}\]/", "<{$html}>$1</{$html}>", $parsed_content);

            if (!empty($replaced_content)) {
                $parsed_content = $replaced_content;
            }
        }

        return $parsed_content;
    }

    /**
     * Returns tags and their HTML replacements.
     *
     * @return array associative array of tags and their HTML replacements.
     */
    private function get_tag_replacements()
    {
        $replacements = array(
          'H1' => 'h1',
          'H2' => 'h2',
          'H3' => 'h3'
        );

        return $replacements;
    }
}
