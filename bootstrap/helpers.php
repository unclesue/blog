<?php

if (!function_exists('class_uses_deep')) {

    /**
     * To get ALL traits including those used by parent classes and other traits.
     *
     * @param $class
     * @param bool $autoload
     *
     * @return array
     */
    function class_uses_deep($class, $autoload = true)
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }
}
