<?php

use TinCan\TCURL;

/**
 * Pagination template.
 *
 * @since 0.03
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$start_at = (!empty($data['start_at'])) ? $data['start_at'] : 1;
$page_params = $data['page_params'];
$total_pages = $data['total_pages'];

$page = $page_params['page'];

unset($page_params['page']);

$base_url = TCURL::create_url($page, $page_params);
?>

<ul class="pagination">
  <?php
  if ($start_at > 1) {
    echo "<li><a href=\"{$base_url}&start_at=".($start_at - 1).'">Prev</a></li>';
  }
  // TODO: Show selection of first / last page numbers with ellipses in the middle.
  // Avoid massive list of pages.
  for ($i = 1; $i <= $total_pages; ++$i) {
    if ($i == $start_at) {
      echo "<li>{$i}</li>";
    }
    else {
      echo "<li><a href=\"{$base_url}&start_at={$i}\">{$i}</a></li>";
    }
  }
  if ($start_at < $total_pages) {
    echo "<li><a href=\"{$base_url}&start_at=".($start_at + 1).'">Next</a></li>';
  }
  ?>
</ul>
