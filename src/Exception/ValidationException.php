<?php

namespace Bleicker\Framework\Exception;

use Bleicker\Exception\ThrowableException as Exception;

/**
 * Class ValidationException
 *
 * @package Bleicker\Framework\Exception
 */
class ValidationException extends Exception {

	const STATUS = 400;
}
