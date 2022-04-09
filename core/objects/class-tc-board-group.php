<?php
/**
 * TODO
 *
 * @package Tin Can
 * @since 0.01
 */

class TCBoardGroup extends TCObject
{
  /**
   * @since 0.01
   */
    public $board_group_id;

    /**
     * @since 0.01
     */
    protected $board_group_name;

    /**
     * @since 0.01
     */
    protected $created_time;

    /**
     * @since 0.01
     */
    protected $updated_time;

    /**
     * TODO
     *
     * @since 0.01
     */
    public function validate_field_value($field_name, $value)
    {
        if (!parent::validate_field_value($field_name, $value)) {
            return false;
        }

        return true;
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_primary_key()
    {
        return 'board_group_id';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_table()
    {
        return 'tc_board_groups';
    }

    /**
     * TODO
     *
     * @since 0.01
     */
    public function get_db_fields()
    {
        return array(
      'board_group_name',
      'created_time',
      'updated_time'
    );
    }
}
