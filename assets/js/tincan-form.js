/**
 * Form submission handler.
 *
 * @since 0.10
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.form = (function($) {

  return {

    submit: function(event) {
      event.preventDefault();

      tincan.form.clear_errors();

      var form = event.target;

      var params = {};

      var field_types = ['input', 'textarea'];

      for (var i = 0; i < field_types.length; i++) {
        $(form).find(field_types[i]).each(function() {
          params[$(this).attr('name')] = $(this).val();
        });
      }

      tincan.http.post(form.action, params, event.data.callback);
    },

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
