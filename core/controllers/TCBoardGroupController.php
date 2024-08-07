<?php

namespace TinCan\controllers;

use TinCan\TCException;
use TinCan\controllers\TCController;
use TinCan\objects\TCBoard;
use TinCan\objects\TCBoardGroup;
use TinCan\objects\TCObject;

/**
 * Board group controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCBoardGroupController extends TCController
{
    /**
     * Creates a new board group.
     *
     * @param string $board_group_name The name of the board group.
     * @param int    $weight           The weight of the board group.
     *
     * @return TCBoardGroup|false The new board group object or false if not saved.
     *
     * @since 0.16
     */
    public function create_board_group($board_group_name, $weight)
    {
        if (empty($board_group_name)) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $board_group = new TCBoardGroup();

        $board_group->board_group_name = $board_group_name;
        $board_group->weight = $weight;
        $board_group->created_time = time();
        $board_group->updated_time = time();

        $new_board_group = null;
        try {
            $new_board_group = $this->db->save_object($board_group);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $new_board_group;
    }

    /**
     * Edits a board group.
     *
     * @param int    $board_group_id   The ID of the board group to edit.
     * @param string $board_group_name The new name of the board group.
     * @param int    $weight           The new weight of the board group.
     *
     * @return TCBoardGroup|false The updated board group object or false if not saved.
     *
     * @since 0.16
     */
    public function edit_board_group($board_group_id, $board_group_name, $weight)
    {
        if (empty($board_group_name)) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $board_group = $this->db->load_object(new TCBoardGroup(), $board_group_id);

        if (empty($board_group)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        $board_group->board_group_name = $board_group_name;
        $board_group->weight = $weight;
        $board_group->updated_time = time();

        $saved_board_group = null;
        try {
            $saved_board_group = $this->db->save_object($board_group);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return $saved_board_group;
    }

    /**
     * Deletes a board group.
     *
     * @param int    $board_group_id         The ID of the board group to delete.
     * @param string $board_fate             The fate of the boards in the group.
     * @param int    $move_to_board_group_id The ID of the board group to move boards to.
     *
     * @return bool True if the board group was deleted, false otherwise.
     *
     * @since 0.16
     */
    public function delete_board_group($board_group_id, $board_fate, $move_to_board_group_id)
    {
        $board_group = $this->db->load_object(new TCBoardGroup(), $board_group_id);

        if (empty($board_group)) {
            $this->error = TCObject::ERR_NOT_FOUND;
            return false;
        }

        try {
            $this->db->delete_object(new TCBoardGroup(), $board_group->board_group_id);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        $boards = $this->db->load_objects(new TCBoard(), null, [['field' => 'board_group_id', 'value' => $board_group->board_group_id]]);

        if ('move' == $board_fate) {
            foreach ($boards as $board) {
                $board->board_group_id = $move_to_board_group_id;

                try {
                    $this->db->save_object($board);
                } catch (TCException $e) {
                    $this->error = TCObject::ERR_NOT_SAVED;
                    return false;
                }
            }
        } else {
            foreach ($boards as $board) {
                try {
                    $this->db->delete_object(new TCBoard(), $board->board_id);
                } catch (TCException $e) {
                    $this->error = TCObject::ERR_NOT_SAVED;
                    return false;
                }
            }
        }

        return true;
    }
}
