<?php
namespace Bleicker\Framework\Validation;

/**
 * Class Results
 *
 * @package Bleicker\Framework\Validation
 */
interface ResultsInterface {

	/**
	 * @return array
	 */
	public static function storage();

	/**
	 * @param string $propertyPath
	 * @param string $propertyValue
	 * @param ResultInterface $result
	 * @return static
	 */
	public static function add($propertyPath, $propertyValue, ResultInterface $result);

	/**
	 * @return static
	 */
	public static function prune();
}