/**
 * Thread action handler.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.thread = (function($) {

  return {

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    $('form#create-thread').submit(function(e) {
      
      const thread_title = $('input[name=thread_title]').val();

      var errors = [];

      // Validate title length.
      if (thread_title.length < min_thread_title) {
        errors.push('Thread title must be at least ' + min_thread_title + ' characters long.');
      }

      if (thread_title.length > max_thread_title) {
        errors.push('Thread title must be no more than ' + max_thread_title + ' characters long.');
      }

      if (errors.length > 0) {
        e.preventDefault();
        tincan.form.clear_errors();
        tincan.form.display_errors($('form#create-thread'), errors);
      }
    });

  });

})(jQuery);
