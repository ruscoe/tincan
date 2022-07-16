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
      var form = event.target;

      var params = {};

      $(form).find('input').each(function() {
        params[$(this).attr('name')] = $(this).val();
      });

      tincan.http.post(form.action, params, $(form).data('callback'));
    }

  };

})(jQuery);
