<?php
$start_at = $data['start_at'];
$page_params = $data['page_params'];
$total_pages = $data['total_pages'];

$base_url = '?';

foreach ($page_params as $name => $value) {
    $base_url .= $name . '=' . $value . '&';
}
?>

<div class="pagination">
  <ul>
    <?php
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li><a href=\"{$base_url}start_at={$i}\">{$i}</a></li>";
    }
    ?>
  </ul>
</div>
