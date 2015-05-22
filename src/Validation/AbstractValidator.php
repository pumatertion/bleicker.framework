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
	 * @var ResultCollection
	 */
	protected $results;

	public function __construct() {
		$this->results = new ResultCollection();
	}

	/**
	 * @return ResultCollection
	 */
	public function getResults() {
		return $this->results;
	}

	/**
	 * @return static
	 */
	public static function create() {
		$reflection = new ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
	}
}
