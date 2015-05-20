<?php

namespace Bleicker\Framework\Validation;

/**
 * Interface MessageInterface
 *
 * @package Bleicker\Framework\Validation
 */
interface MessageInterface {

	const SEVERITY_ERROR = 'Error', SEVERITY_SUCCESS = 'Success', SEVERITY_WARNING = 'Warning', SEVERITY_NOTICE = 'Notice';

	/**
	 * @return string
	 */
	public function getSeverity();

	/**
	 * @return string
	 */
	public function getCode();

	/**
	 * @return string
	 */
	public function getMessage();

	/**
	 * @return mixed
	 */
	public function getSource();

	/**
	 * @param string $propertyPath
	 * @return $this
	 */
	public function setPropertyPath($propertyPath);

	/**
	 * @return string
	 */
	public function getPropertyPath();

	/**
	 * @param string $message
	 * @param integer $code
	 * @param mixed $source
	 * @param string $severity
	 * @return MessageInterface
	 */
	public static function create($message, $code, $source, $severity = self::SEVERITY_ERROR);

	/**
	 * @return string
	 */
	public function __toString();
}
