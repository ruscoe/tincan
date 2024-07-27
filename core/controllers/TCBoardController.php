<?php

namespace TinCan\controllers;

use TinCan\controllers\TCController;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCObject;
use TinCan\objects\TCThread;

/**
 * Board controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCBoardController extends TCController
{
    /**
     * Creates a new board.
     *
     * @param int    $board_group_id The ID of the board group.
     * @param string $board_name     The name of the board.
     *
     * @return TCBoard|false The new board object or false if not saved.
     *
     * @since 0.16
     */
    public function create_board($board_group_id, $board_name)
    {
        $board = new TCBoard();

        $board->board_group_id = $board_group_id;
        $board->board_name = $board_name;
        $board->created_time = time();
        $board->updated_time = time();

        $new_board = null;
        try {
            $new_board = $this->db->save_object($board);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $new_board;
    }

    /**
     * Edits a board.
     *
     * @param int    $board_id       The ID of the board.
     * @param int    $board_group_id The ID of the board group.
     * @param string $board_name     The name of the board.
     *
     * @return TCBoard|false The updated board object or false if not saved.
     *
     * @since 0.16
     */
    public function edit_board($board_id, $board_group_id, $board_name)
    {
        $board = $this->db->load_object(new TCBoard(), $board_id);

        if (empty($board)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $board->board_group_id = $board_group_id;
        $board->board_name = $board_name;
        $board->updated_time = time();

        $saved_board = null;
        try {
            $saved_board = $this->db->save_object($board);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $saved_board;
    }

    /**
     * Deletes a board.
     *
     * @param int    $board_id       The ID of the board to delete.
     * @param string $thread_fate    The fate of the threads in the board.
     * @param int    $move_to_board_id The ID of the board to move threads to.
     *
     * @return bool True if the board was deleted, false otherwise.
     *
     * @since 0.16
     */
    public function delete_board($board_id, $thread_fate, $move_to_board_id)
    {
        $board = $this->db->load_object(new TCBoard(), $board_id);

        if (empty($board)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        try {
            $this->db->delete_object(new TCBoard(), $board->board_id);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $threads = $this->db->load_objects(new TCThread(), null, [['field' => 'board_id', 'value' => $board->board_id]]);

        if ('move' == $thread_fate) {
            foreach ($threads as $thread) {
                $thread->board_id = $move_to_board_id;

                try {
                    $this->db->save_object($thread);
                } catch (TCException $e) {
                    $this->error = TCObject::ERR_NOT_SAVED;
                    return false;
                }
            }
        } else {
            foreach ($threads as $thread) {
                try {
                    $this->db->delete_object(new TCThread(), $thread->thread_id);
                } catch (TCException $e) {
                    $this->error = TCObject::ERR_NOT_SAVED;
                    return false;
                }
            }
        }

        return true;
    }
}
