/**
 * HTTP request handler.
 *
 * @since 0.10
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.http = (function($) {

  return {

    post: function(url, params = [], callback) {
      if (tincan.core.debug) {
        console.log('AJAX post to: ' + url);
      }

      // If JavaScript is enabled, instruct all actions to use AJAX.
      params.ajax = true;

      $.post(url, params).always(callback);
    }

  };

})(jQuery);
