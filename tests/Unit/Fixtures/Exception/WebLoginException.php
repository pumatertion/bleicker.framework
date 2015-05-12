<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\Exception;

use Bleicker\Framework\Security\Vote\Exception\AbstractControllerInvokationException;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleController;

/**
 * Class WebLoginException
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures\Exception
 */
class WebLoginException extends AbstractControllerInvokationException {

	const CONTROLLER_NAME = SimpleController::class, METHOD_NAME = 'loginAction';
}
