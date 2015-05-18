<?php
namespace Bleicker\Framework\Validation\Exception;

/**
 * Interface ValidationExceptionInterface
 *
 * @package Bleicker\Framework\Validation\Exception
 */
interface ValidationExceptionInterface {

	/**
	 * @return ValidationExceptionInterface
	 */
	public static function create();

	/**
	 * @return string
	 */
	public function getControllerName();

	/**
	 * @return array
	 */
	public function getMethodArguments();

	/**
	 * @return string
	 */
	public function getMethodName();

	/**
	 * @param string $controllerName
	 * @return $this
	 */
	public function setControllerName($controllerName);

	/**
	 * @param array $methodArguments
	 * @return $this
	 */
	public function setMethodArguments(array $methodArguments);

	/**
	 * @param string $methodName
	 * @return $this
	 */
	public function setMethodName($methodName);

	/**
	 * @return string
	 */
	public function getMessage();

	/**
	 * @return mixed|integer
	 */
	public function getCode();
}