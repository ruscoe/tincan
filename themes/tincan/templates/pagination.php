<?php

use TinCan\TCURL;
use TinCan\TCPagination;

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

// Don't pass 'page' to TCURL. Will end up duplicated in URL parameters.
unset($page_params['page']);

$base_url = TCURL::create_url($page, $page_params);
?>

<ul class="pagination">
  <?php
  if ($start_at == 1) {
    // User is at the first page and cannot go any further back.
    echo '<li>1</li>';
  }
  else {
    // User is beyond the first page and can go back.
    echo "<li><a href=\"{$base_url}&start_at=".($start_at - 1).'">Prev</a></li>';
    echo "<li><a href=\"{$base_url}&start_at=1\">1</a></li>";
  }

  // Show page links plus / minus $range around the current page.
  $range = 4;

  // First and last page will always appear; trim them from the shown range.
  $range_start = TCPagination::enforce_range(2, ($total_pages - 1), ($start_at - $range));
  $range_end = TCPagination::enforce_range(2, ($total_pages - 1), ($start_at + $range));

  for ($i = $range_start; $i <= $range_end; ++$i) {
    if ($i == $start_at) {
      echo "<li>{$i}</li>";
    } else {
      echo "<li><a href=\"{$base_url}&start_at={$i}\">{$i}</a></li>";
    }
  }

  if ($start_at >= $total_pages) {
    // User is at the last page and cannot go any further.
    echo "<li>{$total_pages}</li>";
  }
  else {
    // Use isn't at the last page yet.
    echo "<li><a href=\"{$base_url}&start_at={$total_pages}\">{$total_pages}</a></li>";
    echo "<li><a href=\"{$base_url}&start_at=".($start_at + 1).'">Next</a></li>';
  }
  ?>
</ul>
