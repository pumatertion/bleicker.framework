<?php
namespace Bleicker\Framework\Validation\Exception;

use Bleicker\Framework\Validation\ResultCollectionInterface;
use Exception;

/**
 * Interface ValidationExceptionInterface
 *
 * @package Bleicker\Framework\Validation\Exception
 */
interface ValidationExceptionInterface {

	/**
	 * @param ResultCollectionInterface $results
	 * @param string $message
	 * @param integer $code
	 * @param Exception $previous
	 * @return ValidationException
	 */
	public static function create(ResultCollectionInterface $results, $message = "", $code = 0, Exception $previous = NULL);

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

	/**
	 * @return ResultCollectionInterface
	 */
	public function getResults();
}