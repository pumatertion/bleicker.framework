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
	 * @param ResultInterface $result
	 * @return static
	 */
	public static function add($propertyPath, ResultInterface $result);

	/**
	 * @return static
	 */
	public static function prune();
}