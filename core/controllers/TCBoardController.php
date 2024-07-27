<?php

namespace TinCan\controllers;

use TinCan\controllers\TCController;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCObject;

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
}
