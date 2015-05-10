<?php

namespace Bleicker\Framework\Security\Vote\Exception;

use Bleicker\Security\Exception\AbstractVoteException;

/**
 * Class AbstractControllerInvokationException
 *
 * @package Bleicker\Framework\Security\Vote\Exception
 */
abstract class AbstractControllerInvokationException extends AbstractVoteException implements ControllerInvokationExceptionInterface {

	const CONTROLLER_NAME = NULL, METHOD_NAME = NULL;

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
