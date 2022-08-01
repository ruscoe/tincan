<?php

/**
 * Renders the contents of the page <header> tag.
 *
 * @since 0.11
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
?>

<?php if ('true' == $settings['enable_css']) { ?>
  <link href="/themes/tincan/css/style.css" rel="stylesheet">
  <link href="/themes/tincan-example/css/style.css" rel="stylesheet">
<?php } ?>

<?php if ('true' == $settings['enable_js']) { ?>
  <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/jquery-ui.min.js"></script>
  <script src="/assets/js/tincan-core.js"></script>
  <script src="/assets/js/tincan-form.js"></script>
  <script src="/assets/js/tincan-http.js"></script>
  <script src="/assets/js/tincan-post.js"></script>
  <script src="/assets/js/tincan-thread.js"></script>
  <script src="/assets/js/tincan-user.js"></script>
<?php } ?>
