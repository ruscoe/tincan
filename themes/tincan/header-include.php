<?php

/**
 * Renders the contents of the page <header> tag.
 *
 * @since 0.03
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
?>

<?php if ('true' == $settings['enable_css']) { ?>
  <link href="/themes/tincan/css/style.css" rel="stylesheet">
<?php } ?>

<?php if ('true' == $settings['enable_js']) { ?>
  <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/tincan-form.js"></script>
  <script src="/assets/js/tincan-post.js"></script>
  <script src="/assets/js/tincan-thread.js"></script>
<?php
  $settings = $data['settings'];

    echo '<script type="text/javascript">';
    echo '  const min_thread_title = ' . $settings['min_thread_title'] . ';';
    echo '  const max_thread_title = ' . $settings['max_thread_title'] . ';';
    echo '</script>';
} ?>
