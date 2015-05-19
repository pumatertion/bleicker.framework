<?php

namespace Bleicker\Framework\Validation;

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
	 * @param mixed $propertyValue
	 * @param ResultInterface $result
	 * @return static
	 */
	public static function add($propertyPath, $propertyValue, ResultInterface $result) {
		$result->setPropertyPath($propertyPath)->setPropertyValue($propertyValue);
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
