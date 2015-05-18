<?php

namespace Bleicker\Framework\Validation;

use Bleicker\Container\AbstractContainer;

/**
 * Class Results
 *
 * @package Bleicker\Framework\Validation
 */
class Results implements ResultsInterface {

	/**
	 * @var array
	 */
	public static $storage = [];

	/**
	 * @param string $propertyPath
	 * @param ResultInterface $result
	 * @return static
	 */
	public static function add($propertyPath, ResultInterface $result) {
		$result->setPropertyPath($propertyPath);
		static::$storage[] = $result;
		return new static;
	}

	/**
	 * @return static
	 */
	public static function prune() {
		static::$storage = [];
		return new static;
	}

	/**
	 * @return array
	 */
	public static function storage() {
		return static::$storage;
	}
}
