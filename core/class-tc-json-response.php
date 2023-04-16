<?php

namespace TinCan;

/**
 * Tin Can JSON response.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.01
 */
class TCJSONResponse
{
    /**
     * @since 0.01
     */
    public $success;

    /**
     * @since 0.01
     */
    public $message;

    /**
     * @since 0.02
     */
    public $errors;

    public function get_output()
    {
        return json_encode($this);
    }
}
