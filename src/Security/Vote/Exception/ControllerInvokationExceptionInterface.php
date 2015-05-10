<?php
namespace Bleicker\Framework\Security\Vote\Exception;

/**
 * Class AbstractControllerInvokationException
 *
 * @package Bleicker\Framework\Security\Vote\Exception
 */
interface ControllerInvokationExceptionInterface {

	const ORIGIN_CONTROLLER_NAME = 'originControllerName', ORIGIN_METHOD_NAME = 'originMethodName', ORIGIN_EXCEPTION_NAME = 'invokedException';

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