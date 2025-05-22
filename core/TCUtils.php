<?php

namespace TinCan;

/**
 * Miscellaneous helper/ utility functions.
 *
 * @package TinCan
 * @author  Ricky Bertram <ricky@rbwebdesigns.co.uk>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 */
class TCUtils
{
    /**
     * Extract unique values from a property in a set of objects.
     *
     * @param object[] Objects to extract property from
     * @param string Property name
     *
     * @return mixed[] Unique values
     */
    public static function get_unique_property_values(array $objects, string $property): array
    {
        $values = array_map(function ($object) use ($property) {
            return $object->{$property};
        }, $objects);

        return array_unique($values);
    }

}
