<?php
/**
 * Post content sanitizer.
 *
 * @since 0.04
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCPostSanitizer
{
  /**
   * Sanitizes the text content of a post.
   *
   * @since 0.04
   *
   * @param string $content the post content
   *
   * @return string the post content sanitized and cleaned up
   */
  public function sanitize_post($content)
  {
    // TODO: Perform all content sanitization.
    $sanitized_content = trim($content);

    return $sanitized_content;
  }
}
