<?php

namespace TinCan;

/**
 * Tin Can base hook.
 *
 * @since 0.14
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
class TCHook
{
    private $hook;

    /**
     * @param string $hook_name the hook to process
     * 
     * @since 0.14
     */
    public function __construct($hook_name)
    {
        $this->hook = $hook_name;
    }

    /**
     * TODO
     *
     * @param mixed $data the data to process via hook implementations
     * 
     * @return mixed processed data
     * 
     * @since 0.14
     */
    public function process($data)
    {
        return $data;
    }
}
