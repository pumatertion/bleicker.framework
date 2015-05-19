<?php

namespace Bleicker\Framework\Validation;

/**
 * Interface ResultInterface
 *
 * @package Bleicker\Framework\Validation
 */
interface ResultInterface {

	/**
	 * @return string
	 */
	public function getCode();

	/**
	 * @return string
	 */
	public function getMessage();

	/**
	 * @return string
	 */
	public function getPropertyPath();

	/**
	 * @return mixed
	 */
	public function getPropertyValue();

	/**
	 * @param string $propertyPath
	 * @return $this
	 */
	public function setPropertyPath($propertyPath);

	/**
	 * @param mixed $propertyValue
	 * @return $this
	 */
	public function setPropertyValue($propertyValue);

	/**
	 * @param string $message
	 * @param integer $code
	 * @return ResultInterface
	 */
	public static function create($message, $code);

	/**
	 * @return string
	 */
	public function __toString();
}
