/**
 * Post action handler.
 *
 * @since 0.10
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

var tincan = tincan || {};

tincan.post = (function($) {

  return {

    // TODO:
    handle_post_reply: function(data) {
      if (tincan.core.debug) {
        console.log(data);
      }
    },

    // TODO:
    handle_update_post: function(data) {
      if (tincan.core.debug) {
        console.log(data);
      }
    },

    // TODO:
    handle_delete_post: function(data) {
      if (tincan.core.debug) {
        console.log(data);
      }
    }

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    $('form#post-reply').submit({callback: tincan.post.handle_post_reply}, tincan.form.submit);
    $('form#update-post').submit({callback: tincan.post.handle_update_post}, tincan.form.submit);

  });

})(jQuery);
