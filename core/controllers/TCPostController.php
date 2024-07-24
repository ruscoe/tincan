<?php

namespace TinCan\controllers;

use TinCan\content\TCPostSanitizer;
use TinCan\controllers\TCController;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;
use TinCan\template\TCPagination;

/**
 * Post controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCPostController extends TCController
{
    /**
     * Determines if a post can be created.
     *
     * @param int $thread_id The ID of the thread the post will be created in.
     *
     * @return bool TRUE if the post can be created, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_create_post($thread_id)
    {
        // Check user has permission to create a new post.
        if (empty($this->user) || !$this->user->can_perform_action(TCUser::ACT_CREATE_POST)) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        // Check this post can be created in the given thread.
        if (empty($error)) {
            $thread = $this->db->load_object(new TCThread(), $thread_id);

            // Validate thread.
            if (empty($thread)) {
                $this->error = TCObject::ERR_NOT_SAVED;
                return false;
            }
        }

        return true;
    }

    /**
     * Creates a new post.
     *
     * @param int    $thread_id    The ID of the thread the post will be created in.
     * @param string $post_content The content of the post.
     *
     * @return TCPost|bool The new post object if successful, otherwise FALSE.
     *
     * @since 0.16
     */
    public function create_post($thread_id, $post_content)
    {
        // Sanitize the post content.
        $post_sanitizer = new TCPostSanitizer();
        $sanitized_post = $post_sanitizer->sanitize_post($post_content);

        if (empty($sanitized_post)) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $new_post = null;

        $post = new TCPost();
        $post->user_id = $this->user->user_id;
        $post->thread_id = $thread_id;
        $post->content = $sanitized_post;
        $post->created_time = time();
        $post->updated_time = time();
        $post->updated_by_user = $this->user->user_id;

        try {
            $new_post = $this->db->save_object($post);
        } catch (TCException $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $new_post;
    }

    /**
     * Gets the total number of pages in a thread.
     *
     * @param int $thread_id The ID of the thread.
     *
     * @return int The total number of pages in the thread.
     *
     * @since 0.16
     */
    public function get_total_thread_pages($thread_id)
    {
        $conditions = [
            ['field' => 'thread_id', 'value' => $thread_id],
          ];

        var_dump($this->settings);

        $total_posts = $this->db->count_objects(new TCPost(), $conditions);
        $total_pages = TCPagination::calculate_total_pages($total_posts, $this->settings['posts_per_page']);

        return $total_pages;
    }
}
