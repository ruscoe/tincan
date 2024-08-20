/**
 * Post action handler.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

(function($) {

  $(document).ready(function() {

    $('.reply a').click(function(e) {
      e.preventDefault();

      const markup = 'Replying to [' + $(this).data('author') + '](/index.php?page=' + $(this).data('page') + '&thread=' + $(this).data('thread') + '&start_at=' + $(this).data('threadpage') + '#post-' + $(this).data('post') + '):' + "\n\n";

      const post_content = $('textarea[name=post_content]');

      post_content.append(markup);

      post_content[0].scrollIntoView();
    });

  });

})(jQuery);
