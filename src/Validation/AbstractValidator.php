<?php

namespace Bleicker\Framework\Validation;

use ReflectionClass;

/**
 * Class AbstractValidator
 *
 * @package Bleicker\Framework\Validation
 */
abstract class AbstractValidator implements ValidatorInterface {

	/**
	 * @return static
	 */
	public static function create() {
		$reflection = new ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
	}
}
