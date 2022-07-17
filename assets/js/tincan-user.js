/**
 * User action handler.
 *
 * @since 0.10
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.user = (function($) {

  return {

    handle_log_in: function(data) {
      if (tincan.core.debug) {
        console.log(data);
      }

      if (data.success) {
        window.location.href = '/';
      }
      else {
        tincan.form.display_errors($('form#log-in'), [data.errors]);
      }
    }

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    $('form#log-in').submit({callback: tincan.user.handle_log_in}, tincan.form.submit);

  });

})(jQuery);
