<?php

namespace Bleicker\Framework\Validation;

/**
 * Class Result
 *
 * @package Bleicker\Framework\Validation
 */
abstract class AbstractResult implements ResultInterface {

	/**
	 * @var string
	 */
	protected $propertyPath;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var array
	 */
	protected $arguments;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @param string $message
	 * @param integer $code
	 * @param array $arguments
	 */
	public function __construct($message, $code, array $arguments = array()) {
		$this->message = $message;
		$this->code = $code;
		$this->arguments = $arguments;
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function getPropertyPath() {
		return $this->propertyPath;
	}

	/**
	 * @param string $propertyPath
	 * @return $this
	 */
	public function setPropertyPath($propertyPath) {
		$this->propertyPath = $propertyPath;
		return $this;
	}

	/**
	 * @param string $message
	 * @param integer $code
	 * @param array $arguments
	 * @return AbstractResult
	 */
	public static function create($message, $code, array $arguments = array()) {
		return new static($message, $code, $arguments);
	}
}
