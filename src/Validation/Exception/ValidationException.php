<?php

namespace Bleicker\Framework\Validation\Exception;

use Bleicker\Exception\ThrowableException as Exception;
use Bleicker\Framework\Validation\ResultCollectionInterface;
use Exception as OriginException;

/**
 * Class ValidationException
 *
 * @package Bleicker\Framework\Validation\Exception
 */
class ValidationException extends Exception implements ValidationExceptionInterface {

	const STATUS = 400;

	/**
	 * @var string
	 */
	protected $controllerName;

	/**
	 * @var string
	 */
	protected $methodName;

	/**
	 * @var array
	 */
	protected $methodArguments;

	/**
	 * @var ResultCollectionInterface
	 */
	protected $results;

	/**
	 * @param ResultCollectionInterface $results
	 * @param string $message
	 * @param integer $code
	 * @param OriginException $previous
	 */
	public function __construct(ResultCollectionInterface $results, $message = "", $code = 0, OriginException $previous = NULL) {
		parent::__construct($message, $code, $previous);
		$this->results = $results;
	}

	/**
	 * @param ResultCollectionInterface $results
	 * @param string $message
	 * @param integer $code
	 * @param OriginException $previous
	 * @return ValidationException
	 */
	public static function create(ResultCollectionInterface $results, $message = "", $code = 0, OriginException $previous = NULL) {
		return new static($results, $message, $code, $previous);
	}

	/**
	 * @param string $controllerName
	 * @return $this
	 */
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
		return $this;
	}

	/**
	 * @param array $methodArguments
	 * @return $this
	 */
	public function setMethodArguments(array $methodArguments) {
		$this->methodArguments = $methodArguments;
		return $this;
	}

	/**
	 * @param string $methodName
	 * @return $this
	 */
	public function setMethodName($methodName) {
		$this->methodName = $methodName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getControllerName() {
		return $this->controllerName;
	}

	/**
	 * @return array
	 */
	public function getMethodArguments() {
		return $this->methodArguments;
	}

	/**
	 * @return string
	 */
	public function getMethodName() {
		return $this->methodName;
	}

	/**
	 * @return ResultCollectionInterface
	 */
	public function getResults() {
		return $this->results;
	}
}
