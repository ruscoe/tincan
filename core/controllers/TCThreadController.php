<?php

namespace TinCan\controllers;

use TinCan\TCException;
use TinCan\content\TCPostSanitizer;
use TinCan\controllers\TCController;
use TinCan\objects\TCBoard;
use TinCan\objects\TCObject;
use TinCan\objects\TCPost;
use TinCan\objects\TCThread;
use TinCan\objects\TCUser;
use TinCan\template\TCPagination;

/**
 * Thread controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCThreadController extends TCController
{
    /**
     * Determines if a thread can be created.
     *
     * @param int $board_id The ID of the board the thread will be created in.
     *
     * @return bool TRUE if the thread can be created, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_create_thread($board_id)
    {
        // Check user has permission to create a new thread.
        if ((empty($this->user) || !$this->user->can_perform_action(TCUser::ACT_CREATE_THREAD))) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        // Check this thread can be created in the given board.
        $board = (!empty($board_id)) ? $this->db->load_object(new TCBoard(), $board_id) : null;

        if (empty($board)) {
            // Board doesn't exist.
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    /**
     * Creates a new thread.
     *
     * @param int    $board_id     The ID of the board the thread will be created in.
     * @param string $thread_title The title of the thread.
     * @param string $post_content The content of the thread's initial post.
     *
     * @return TCThread|bool The new thread object if successful, otherwise FALSE.
     *
     * @since 0.16
     */
    public function create_thread($board_id, $thread_title, $post_content)
    {
        // Validate thread title.
        $thread_title = trim($thread_title);

        if (empty($thread_title) || (strlen($thread_title) < $this->settings['min_thread_title'])) {
            $this->error = TCThread::ERR_TITLE_SHORT;
            return false;
        }

        if (strlen($thread_title) > $this->settings['max_thread_title']) {
            $this->error = TCThread::ERR_TITLE_LONG;
            return false;
        }

        // Sanitize the post content.
        $post_sanitizer = new TCPostSanitizer();
        $sanitized_post = $post_sanitizer->sanitize_post($post_content);

        if (empty($sanitized_post)) {
            $this->error = TCObject::ERR_EMPTY_FIELD;
            return false;
        }

        $thread = new TCThread();
        $thread->board_id = $board_id;
        $thread->thread_title = $thread_title;
        $thread->first_post_id = 0;
        $thread->created_by_user = $this->user->user_id;
        $thread->updated_by_user = $this->user->user_id;
        $thread->created_time = time();
        $thread->updated_time = time();

        try {
            $new_thread = $this->db->save_object($thread);
        } catch (TCException $e) {
            $new_thread = null;
        }

        if (!empty($new_thread)) {
            // Create the thread's initial post.
            $post = new TCPost();
            $post->user_id = $this->user->user_id;
            $post->thread_id = $new_thread->thread_id;
            $post->content = $sanitized_post;
            $post->created_time = time();
            $post->updated_time = time();
            $post->updated_by_user = $this->user->user_id;

            $new_post = $this->db->save_object($post);

            if (!empty($new_post)) {
                // Assign first post ID for this thread.
                $new_thread->first_post_id = $new_post->post_id;
                $this->db->save_object($new_thread);
            } else {
                // Delete thread and exit with error if post cannot be created.
                $this->error = TCObject::ERR_NOT_SAVED;
                $this->db->delete_object($thread, $thread->thread_id);
                return false;
            }

            return $new_thread;
        }

        return false;
    }

    /**
     * Edits a thread.
     *
     * @param int $thread_id The ID of the thread to be edited.
     *
     * @return bool TRUE if the thread can be edited, otherwise FALSE.
     *
     * @since 0.16
     */
    public function edit_thread($thread_id, $thread_title, $board_id)
    {
        $thread = $this->db->load_object(new TCThread(), $thread_id);

        if (empty($thread)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $thread->thread_title = $thread_title;
        $thread->board_id = $board_id;
        $thread->updated_time = time();

        try {
            $saved_thread = $this->db->save_object($thread);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $saved_thread;
    }

    /**
     * Determines if a thread can be deleted.
     *
     * @param int $thread_id The ID of the thread to be deleted.
     *
     * @return bool TRUE if the thread can be deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_delete_thread($thread_id)
    {
        $thread = $this->db->load_object(new TCThread(), $thread_id);

        if (empty($thread)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Check user has permission to delete this thread.
        if ((empty($this->user) || !$this->user->can_delete_thread($thread))) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        return true;
    }

    /**
     * Marks a thread as deleted.
     *
     * @param int $thread_id The ID of the thread to be deleted.
     *
     * @return bool TRUE if the thread is deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function delete_thread($thread_id)
    {
        $thread = $this->db->load_object(new TCThread(), $thread_id);

        $thread->deleted = true;

        try {
            $this->db->save_object($thread);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    /**
     * Permanently deletes a thread.
     *
     * @param int $thread_id The ID of the thread to be deleted.
     *
     * @return bool TRUE if the thread is deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function permanently_delete_thread($thread_id)
    {
        $thread = $this->db->load_object(new TCThread(), $thread_id);

        if (empty($thread)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        try {
            $this->db->delete_object(new TCThread(), $thread->thread_id);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }
}
