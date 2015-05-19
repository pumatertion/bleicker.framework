<?php
namespace Bleicker\Framework\Security\Vote\Exception;

/**
 * Class AbstractControllerInvokationException
 *
 * @package Bleicker\Framework\Security\Vote\Exception
 */
interface ControllerInvokationExceptionInterface {

	const CONTROLLER_NAME = NULL, METHOD_NAME = NULL;

	/**
	 * @return string
	 */
	public function getControllerName();

	/**
	 * @return string
	 */
	public function getMethodName();

	/**
	 * @return string
	 */
	public function getMessage();

	/**
	 * @return mixed|integer
	 */
	public function getCode();
}