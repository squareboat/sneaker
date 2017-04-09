<?php

namespace SquareBoat\Sneaker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SquareBoat\Sneaker\Sneaker
 */
class Sneaker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sneaker';
    }
}
