<?php

namespace Bleicker\Framework\Security\Vote\Exception;

use Bleicker\Security\Exception\AbstractVoteException;

/**
 * Class AbstractControllerInvocationException
 *
 * @package Bleicker\Framework\Security\Vote\Exception
 */
abstract class AbstractControllerInvocationException extends AbstractVoteException implements ControllerInvocationExceptionInterface {

	/**
	 * @return string
	 */
	public function getControllerName() {
		return static::CONTROLLER_NAME;
	}

	/**
	 * @return string
	 */
	public function getMethodName() {
		return static::METHOD_NAME;
	}
}
