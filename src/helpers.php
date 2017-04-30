<?php

if (! function_exists('array_flat')) {
    /**
     * Flatten a multi-dimensional array into a single level while preserving the keys.
     *
     * @param  array  $array
     * @param  string  $prefix
     * @return array
     */
    function array_flat($array, $prefix = '')
    {
        $result = [];

        foreach($array as $key => $item) {
            $item = $item instanceof Illuminate\Support\Collection ? $item->all() : $item;

            if(is_array($item)) {
                $result = $result + array_flat($item, $prefix . $key . '.');
            } else {
                $result[$prefix.$key] = $item;
            }
        }

        return $result;
    }
}
