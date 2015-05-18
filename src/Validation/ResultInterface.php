<?php

namespace Bleicker\Framework\Validation;

/**
 * Interface ResultInterface
 *
 * @package Bleicker\Framework\Validation
 */
interface ResultInterface {

	/**
	 * @return array
	 */
	public function getArguments();

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
	 * @param string $propertyPath
	 * @return $this
	 */
	public function setPropertyPath($propertyPath);

	/**
	 * @param string $message
	 * @param integer $code
	 * @param array $arguments
	 * @return ResultInterface
	 */
	public static function create($message, $code, array $arguments = array());
}
