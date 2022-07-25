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

    handle_post_reply: function(data) {
      if (tincan.core.debug) {
        console.log('handle_post_reply');
        console.log(data);
      }

      if (data.success) {
        window.location.href = data.target_url;
      }
      else {
        // TODO: Error message.
      }
    },

    handle_update_post: function(data) {
      if (tincan.core.debug) {
        console.log('handle_update_post');
        console.log(data);
      }

      if (data.success) {
        window.location.href = data.target_url;
      }
      else {
        // TODO: Error message.
      }
    },

    handle_delete_post: function(data) {
      if (tincan.core.debug) {
        console.log('handle_delete_post');
        console.log(data);
      }

      if (data.success && data.post_id) {
        $('#post-' + data.post_id).hide('fast');
      }
    },

    confirm_delete_post: function(post_id) {
      if (tincan.core.debug) {
        console.log('confirm_delete_post');
        console.log('Confirmed deletion of post: ' + post_id);
      }

      tincan.http.post('/actions/delete-post.php', {post_id: post_id}, tincan.post.handle_delete_post);
    },

    create_delete_post_dialog: function(post_id) {
      // Destroy post dialog if one already exists.
      if ($('#delete-post-dialog').dialog('instance')) {
        $('#delete-post-dialog').dialog('destroy');
      }

      $('#delete-post-dialog').data('post', post_id);

      $('#delete-post-dialog').dialog({
        autoOpen: false,
        draggable: false,
        position: {my: 'center', at: 'center', of: '#post-' + post_id },
        width: 400,
        buttons: [
          {
            text: 'Delete post',
            click: function() {
              $(this).dialog('close');
              tincan.post.confirm_delete_post($(this).data('post'));
            }
          },
          {
            text: 'Cancel',
            click: function() {
              $(this).dialog('close');
            }
          }
        ]
      });
    }

  };

})(jQuery);

(function($) {

  $(document).ready(function() {

    // Form submit handlers.
    $('form#post-reply').submit({callback: tincan.post.handle_post_reply}, tincan.form.submit);
    $('form#update-post').submit({callback: tincan.post.handle_update_post}, tincan.form.submit);

    // Initialize delete post dialog box.
    tincan.post.create_delete_post_dialog();

    // Click handler for post delete links.
    $('.post-controls .delete').click(function(e) {
      e.preventDefault();

      var post_id = $(this).parent().data('post');

      if (tincan.core.debug) {
        console.log('Clicked delete on post: ' + post_id);
      }

      tincan.post.create_delete_post_dialog(post_id);

      $('#delete-post-dialog').dialog('open');
    });

  });

})(jQuery);
