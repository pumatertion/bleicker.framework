<?php

namespace Tests\Bleicker\Framework\Functional\Security\Exception;

use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvokationException;
use Tests\Bleicker\Framework\Functional\Fixtures\LoginController;

/**
 * Class WebLoginException
 *
 * @package Tests\Bleicker\Framework\Functional\Security\Exception
 */
class WebLoginException extends AbstractControllerInvokationException {

	const CONTROLLER_NAME = LoginController::class, METHOD_NAME = 'indexAction';
}
