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
      console.log('handle_log_in');
      console.log(data);
    }

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    $('form#log-in').submit({callback: tincan.user.handle_log_in}, tincan.form.submit);

  });

})(jQuery);
