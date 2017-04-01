<?php

namespace SquareBoat\Sneaker\Exceptions;

use Exception;

class DummyException extends Exception
{
	/**
	* @var string
	*/
	protected $message = 'The dummy exception.';
}
