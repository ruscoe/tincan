/**
 * Thread action handler.
 *
 * @since 0.10
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.thread = (function($) {

  return {

    handle_create_thread: function(data) {
      if (tincan.core.debug) {
        console.log(data);
      }

      if (data.success) {
        window.location.href = data.target_url;
      }
      else {
        tincan.form.display_errors($('form#create-thread'), [data.errors]);
      }
    }

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    $('form#create-thread').submit({callback: tincan.thread.handle_create_thread}, tincan.form.submit);

  });

})(jQuery);
