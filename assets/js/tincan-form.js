/**
 * Form submission handler.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.form = (function($) {

  return {

    display_errors: function(form, errors = []) {
      var markup = '<div class="message-box"><ul class="errors">';
      for (var i = 0; i < errors.length; i++) {
        markup += '<li>' + errors[i] + '</li>';
      }
      markup += '</ul></div>';

      $(form).before(markup);
    },

    clear_errors: function() {
      $('.message-box').remove();
    }

  };

})(jQuery);
