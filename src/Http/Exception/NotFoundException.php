<?php

namespace Bleicker\Framework\Http\Exception;

use Bleicker\Exception\ThrowableException as Exception;

/**
 * Class NotFoundException
 *
 * @package Bleicker\Framework\Http\Exception
 */
class NotFoundException extends Exception {

	const STATUS = 404;
}
