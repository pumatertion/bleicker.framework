<?php

namespace Tests\Bleicker\Framework\Functional\Security;

use Bleicker\Framework\WebApplication;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\Vote;
use Tests\Bleicker\Framework\Functional\Fixtures\Exception\AccessDeniedException;
use Tests\Bleicker\Framework\Functional\Fixtures\SecuredController;
use Tests\Bleicker\Framework\Functional\Security\Exception\WebLoginException;
use Tests\Bleicker\Framework\FunctionalTestCase;

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

	/**
	 * @test
	 */
	public function unsecuredControllerTest() {
		$_SERVER['REQUEST_URI'] = '/open';
		$_SERVER['REQUEST_METHOD'] = 'get';

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/open', 'get', new ControllerRouteData(SecuredController::class, 'indexAction'));

		Vote::register('SecuredController', function () {
			$this->assertTrue(TRUE);
		}, SecuredController::class . '::indexAction');

		ob_start();
		$webApplication = new WebApplication();
		$webApplication->run();
		$result = ob_get_contents();
		ob_end_clean();
		$this->assertEquals('foo', $result);
	}

	/**
	 * @test
	 */
	public function showLoginBoxTest() {
		$_SERVER['REQUEST_URI'] = '/profile';
		$_SERVER['REQUEST_METHOD'] = 'get';

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/profile', 'get', new ControllerRouteData(SecuredController::class, 'indexAction'));

		Vote::register('SecuredController', function () {
			throw new WebLoginException('TestException', 1431289336);
		}, SecuredController::class . '::indexAction');

		ob_start();
		$webApplication = new WebApplication();
		$webApplication->run();
		$result = ob_get_contents();
		ob_end_clean();
		$this->assertEquals(SecuredController::class . '::indexAction::TestException::1431289336', $result);
	}
}
