<?php

namespace TinCan\controllers;

use TinCan\TCException;
use TinCan\content\TCPostSanitizer;
use TinCan\controllers\TCController;
use TinCan\objects\TCObject;
use TinCan\objects\TCPost;
use TinCan\objects\TCReport;
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
        $thread = $this->db->load_object(new TCThread(), $thread_id);

        // Validate thread.
        if (empty($thread)) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        // Cannot reply to a locked thread.
        if ($thread->locked) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
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
            $this->error = TCObject::ERR_EMPTY_FIELD;
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
            $this->error = TCOBject::ERR_NOT_SAVED;
            return false;
        }

        return $new_post;
    }

    /**
     * Determines if a post can be deleted.
     *
     * @param int $post_id The ID of the post to be deleted.
     *
     * @return bool TRUE if the post can be deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_delete_post($post_id)
    {
        $post = $this->db->load_object(new TCPost(), $post_id);

        if (empty($post)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Check user has permission to delete this post.
        if ((empty($this->user) || !$this->user->can_delete_post($post))) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        return true;
    }

    /**
     * Deletes a post.
     *
     * @param int $post_id The ID of the post to be deleted.
     *
     * @return bool TRUE if the post was deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function delete_post($post_id)
    {
        $post = $this->db->load_object(new TCPost(), $post_id);

        $post->deleted = true;

        try {
            $this->db->save_object($post);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    /**
     * Determines if a post can be updated.
     *
     * @param int $post_id The ID of the post to be updated.
     *
     * @return bool TRUE if the post can be updated, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_update_post($post_id)
    {
        $post = $this->db->load_object(new TCPost(), $post_id);

        if (empty($post)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Check user has permission to delete this post.
        if ((empty($this->user) || !$this->user->can_edit_post($post))) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        return true;
    }

    /**
     * Updates a post.
     *
     * @param int    $post_id      The ID of the post to be updated.
     * @param string $post_content The new content of the post.
     *
     * @return TCPost|bool The updated post object if successful, otherwise FALSE.
     *
     * @since 0.16
     */
    public function update_post($post_id, $post_content)
    {
        $post = $this->db->load_object(new TCPost(), $post_id);

        // Sanitize the post content.
        $post_sanitizer = new TCPostSanitizer();
        $sanitized_post = $post_sanitizer->sanitize_post($post_content);

        if (empty($sanitized_post)) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $updated_post = null;

        $post->content = $sanitized_post;
        $post->updated_time = time();
        $post->updated_by_user = $this->user->user_id;

        try {
            $updated_post = $this->db->save_object($post);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $updated_post;
    }

    /**
     * Determines if a post can be reported.
     *
     * @param int $post_id The ID of the post to be reported.
     *
     * @return bool TRUE if the post can be reported, otherwise FALSE.
     *
     * @since 0.16
     */
    public function can_report_post($post_id)
    {
        $post = $this->db->load_object(new TCPost(), $post_id);

        if (empty($post)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        // Check user has permission to report this post.
        if ((empty($this->user) || !$this->user->can_report_post($post))) {
            $this->error = TCUser::ERR_NOT_AUTHORIZED;
            return false;
        }

        return true;
    }

    /**
     * Reports a post.
     *
     * @param int    $post_id The ID of the post to be reported.
     * @param string $reason  The reason for reporting the post.
     *
     * @return TCReport|bool The new report object if successful, otherwise FALSE.
     *
     * @since 0.16
     */
    public function report_post($post_id, $reason)
    {
        // Sanitize the report reason.
        $post_sanitizer = new TCPostSanitizer();
        $sanitized_reason = $post_sanitizer->sanitize_post($reason);

        if (empty($sanitized_reason)) {
            $this->error = TCObject::ERR_EMPTY_FIELD;
            return false;
        }

        $new_report = null;

        $report = new TCReport();
        $report->user_id = $this->user->user_id;
        $report->post_id = $post_id;
        $report->reason = $sanitized_reason;
        $report->created_time = time();
        $report->updated_time = time();

        try {
            $new_report = $this->db->save_object($report);
        } catch (TCException $e) {
            var_dump($e);
            exit;
            $this->error = TCOBject::ERR_NOT_SAVED;
            return false;
        }

        return $new_report;
    }

    /**
     * Deletes a report.
     *
     * @param int $report_id The ID of the report to delete.
     *
     * @return bool True if the report has been deleted, false otherwise.
     *
     * @since 0.16
     */
    public function delete_report($report_id)
    {
        $report = $this->db->load_object(new TCReport(), $report_id);

        if (empty($report)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        try {
            $this->db->delete_object(new TCReport(), $report->report_id);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
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

        $total_posts = $this->db->count_objects(new TCPost(), $conditions);
        $total_pages = TCPagination::calculate_total_pages($total_posts, $this->settings['posts_per_page']);

        return $total_pages;
    }
}
