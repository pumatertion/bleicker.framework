<?php

namespace Bleicker\Framework\Validation;

use ReflectionClass;

/**
 * Class Result
 *
 * @package Bleicker\Framework\Validation
 */
class Message implements MessageInterface {

	/**
	 * @var string
	 */
	protected $severity;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var mixed
	 */
	protected $source;

	/**
	 * @var string
	 */
	protected $propertyPath;

	/**
	 * @param string $message
	 * @param integer $code
	 * @param mixed $source
	 * @param string $severity
	 */
	public function __construct($message, $code, $source, $severity = self::SEVERITY_ERROR) {
		$this->message = $message;
		$this->code = $code;
		$this->source = $source;
		$this->severity = $severity;
	}

	/**
	 * @param string $message
	 * @param integer $code
	 * @param mixed $source
	 * @param string $severity
	 * @return static
	 */
	public static function create($message, $code, $source, $severity = self::SEVERITY_ERROR) {
		$reflection = new ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
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
	 * @return string
	 */
	public function getPropertyPath() {
		return $this->propertyPath;
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
	 * @return mixed
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @return string
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getSeverity() . '('.$this->getCode().'): ' . $this->getMessage();
	}
}
