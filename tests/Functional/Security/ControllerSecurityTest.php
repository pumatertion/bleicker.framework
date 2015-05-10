<?php

namespace Tests\Bleicker\Framework\Functional\Security;

use Bleicker\Framework\WebApplication;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\Vote;
use Tests\Bleicker\Framework\FunctionalTestCase;
use Tests\Bleicker\Framework\Functional\Fixtures\Exception\AccessDeniedException;
use Tests\Bleicker\Framework\Functional\Fixtures\SecuredController;

/**
 * Class ControllerSecurityTest
 *
 * @package Tests\Bleicker\Framework\Functional\Security
 */
class ControllerSecurityTest extends FunctionalTestCase {

	/**
	 * @test
	 * @expectedException \Tests\Bleicker\Framework\Functional\Fixtures\Exception\AccessDeniedException
	 */
	public function securedControllerTest() {
		$_SERVER['REQUEST_URI'] = '/secure';
		$_SERVER['REQUEST_METHOD'] = 'get';

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/secure', 'get', new ControllerRouteData(SecuredController::class, 'indexAction'));

		Vote::register('SecuredController', function () {
			throw new AccessDeniedException;
		}, SecuredController::class . '::indexAction');

		$webApplication = new WebApplication();
		$webApplication->run();
	}
}
