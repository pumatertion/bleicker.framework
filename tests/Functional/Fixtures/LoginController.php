<?php

namespace Tests\Bleicker\Framework\Functional\Fixtures;

use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvokationExceptionInterface;

/**
 * Class LoginController
 *
 * @package Tests\Bleicker\Framework\Functional\Fixtures
 */
class LoginController extends AbstractController {

	/**
	 * @param string $originControllerName
	 * @param string $originMethodName
	 * @param ControllerInvokationExceptionInterface $invokedException
	 * @return string
	 */
	public function indexAction($originControllerName = NULL, $originMethodName = NULL, ControllerInvokationExceptionInterface $invokedException = NULL) {
		return $originControllerName . '::' . $originMethodName . '::' . $invokedException->getMessage() . '::' . $invokedException->getCode();
	}
}
