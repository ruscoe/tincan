<?php
/**
 * Pagination functionality.
 *
 * @package Tin Can
 * @since 0.02
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

class TCPagination {

  /**
   * Calculates total pages.
   *
   * Rounds up to ensure partial pages are included.
   *
   * @since 0.02
   *
   * @param int $count the total number of items to be shown
   * @param int $per_page the number of items to show per page
   *
   * @return int number of pages required to show all items
   */
  static function calculate_total_pages($count, $per_page) {
    return ($count <= $per_page) ? 1 : ceil($count / $per_page);
  }

  /**
   * Calculates the amount to offset results by for a given page.
   *
   * @since 0.02
   *
   * @param int $start_page the page on which to show items
   * @param int $per_page the number of items to show per page
   *
   * @return int the number of items to offset from the start of the results.
   */
  static function calculate_page_offset($start_page, $per_page) {
    // Records start at 0, so results on page 1 are offset by 0,
    // page 2 offset by 1, etc.
    $offset = ($start_page > 1) ? ($start_page - 1) : 0;

    // Multiply by items per page for final offset.
    $offset *= $per_page;

    return $offset;
  }

}
