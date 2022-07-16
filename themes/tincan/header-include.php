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
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/tincan-core.js"></script>
  <script src="/assets/js/tincan-form.js"></script>
  <script src="/assets/js/tincan-http.js"></script>
  <script src="/assets/js/tincan-user.js"></script>
<?php } ?>
