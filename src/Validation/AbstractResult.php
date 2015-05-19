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
	 * @var mixed
	 */
	protected $propertyValue;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @param string $message
	 * @param integer $code
	 */
	public function __construct($message, $code) {
		$this->message = $message;
		$this->code = $code;
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
	 * @return mixed
	 */
	public function getPropertyValue() {
		return $this->propertyValue;
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
	 * @param string $propertyValue
	 * @return $this
	 */
	public function setPropertyValue($propertyValue) {
		$this->propertyValue = $propertyValue;
		return $this;
	}

	/**
	 * @param string $message
	 * @param integer $code
	 * @return AbstractResult
	 */
	public static function create($message, $code) {
		return new static($message, $code);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf($this->getMessage(), $this->getCode(), $this->getPropertyPath(), $this->getPropertyValue());
	}
}
