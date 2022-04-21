<?php

namespace TinCan;

/**
 * Represents an image file.
 *
 * @since 0.05
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCImage
{
  public const ERR_FILE_TYPE = 'file-type';
  public const ERR_FILE_SIZE = 'file-size';
  public const ERR_FILE_GENERAL = 'file-gen';

  // Maximum file size is 1MB. Value below is bytes.
  public const MAX_FILE_SIZE = 1000000;

  /**
   * @since 0.05
   */
  protected $name;

  /**
   * @since 0.05
   */
  protected $file_type;

  /**
   * @since 0.05
   */
  protected $mime_type;

  /**
   * @since 0.05
   */
  protected $size;

  /**
   * TODO
   *
   * @since 0.05
   */
  public function scale_to_square($source_image, $size) {
    if (empty($size)) {
      return null;
    }

    $image = null;

    if ($this->file_type == IMAGETYPE_JPEG) {
      $image = imagecreatefromjpeg($source_image);
    }
    else if ($this->file_type == IMAGETYPE_PNG) {
      $image = imagecreatefrompng($source_image);
    }

    $scaled_image = imagescale($image, $size, $size, IMG_BICUBIC);

    return $scaled_image;
  }

  /**
   * Determines if this image is a valid format.
   *
   * @since 0.05
   *
   * @return bool true if image format is valid
   */
  public function is_valid_type()
  {
    if ((IMAGETYPE_JPEG != $this->file_type) && (IMAGETYPE_PNG != $this->file_type)) {
      return false;
    }

    if (('image/jpeg' != $this->mime_type) && ('image/png' != $this->mime_type)) {
      return false;
    }

    return true;
  }

  /**
   * Determines if this image is below the maximum file size.
   *
   * @since 0.05
   *
   * @return bool true if image size is valid
   */
  public function is_valid_size()
  {
    return $this->size <= self::MAX_FILE_SIZE;
  }

  /**
   * @since 0.05
   */
  public function __get($name)
  {
    return $this->$name;
  }

  /**
   * @since 0.05
   */
  public function __set($name, $value)
  {
    $this->$name = $value;
  }
}
