<?php

namespace Tests\Bleicker\Framework\Unit;

use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\Framework\ApplicationFactory;
use Bleicker\Framework\Context\ContextInterface;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\Response;
use Bleicker\Framework\HttpApplicationInterface;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\HttpApplicationResponseInterface;
use Bleicker\Framework\RequestHandlerInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Routing\ControllerRouteData;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\SecurityManagerInterface;
use Bleicker\Security\Vote;
use Bleicker\Security\Votes;
use Bleicker\Translation\Locale;
use Bleicker\Translation\Locales;
use Bleicker\Translation\LocalesInterface;
use Tests\Bleicker\Framework\Unit\Fixtures\Exception\AccessDeniedException;
use Tests\Bleicker\Framework\Unit\Fixtures\EntityManager;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleController;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ApplicationFactoryTest
 *
 * @package Tests\Bleicker\Framework\Unit
 */
class ApplicationFactoryTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		ObjectManager::prune();
		Converter::prune();
		Locale::register('German', 'de', 'DE');
		ObjectManager::add(EntityManagerInterface::class, EntityManager::class);
	}

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::prune();
		Converter::prune();
		Locales::prune();
		Votes::prune();
	}

	/**
	 * @test
	 */
	public function objectsExistTest() {
		ApplicationFactory::http();
		$this->assertInstanceOf(HttpApplicationInterface::class, ObjectManager::get(HttpApplicationInterface::class), 'Application exists');
		$this->assertInstanceOf(HttpApplicationRequestInterface::class, ObjectManager::get(HttpApplicationRequestInterface::class), 'Application Request exists');
		$this->assertInstanceOf(HttpApplicationResponseInterface::class, ObjectManager::get(HttpApplicationResponseInterface::class), 'Application Response exists');
		$this->assertInstanceOf(SecurityManagerInterface::class, ObjectManager::get(SecurityManagerInterface::class), 'SecurityManager exists');
		$this->assertInstanceOf(RouterInterface::class, ObjectManager::get(RouterInterface::class), 'Router exists');
		$this->assertInstanceOf(ContextInterface::class, ObjectManager::get(ContextInterface::class), 'Context exists');
		$this->assertInstanceOf(ConverterInterface::class, ObjectManager::get(ConverterInterface::class), 'Converter exists');
		$this->assertInstanceOf(LocalesInterface::class, ObjectManager::get(LocalesInterface::class), 'Locales exists');
		$this->assertInstanceOf(RequestHandlerInterface::class, ObjectManager::get(RequestHandlerInterface::class), 'RequestHandler exists');
		$this->assertInstanceOf(Request::class, ObjectManager::get(Request::class), 'Http Request exists');
		$this->assertInstanceOf(Response::class, ObjectManager::get(Response::class), 'Http Response exists');
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Framework\Http\Exception\NotFoundException
	 */
	public function runApplicationTest() {
		ApplicationFactory::http()->run();
	}

	/**
	 * @test
	 * @expectedException \Tests\Bleicker\Framework\Unit\Fixtures\Exception\AccessDeniedException
	 */
	public function callSecuredControllerTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_URI', '/secure?bar=baz');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'GET');

		ApplicationFactory::http();

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/secure', 'get', new ControllerRouteData(SimpleController::class, 'indexAction'));

		Vote::register('securedController', function(){
			throw new AccessDeniedException();
		}, SimpleController::class .'::.*');

		ApplicationFactory::http()->run();
	}

	/**
	 * @test
	 */
	public function callControllerTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_URI', '/foo?bar=baz');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'GET');

		ApplicationFactory::http();

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/foo', 'get', new ControllerRouteData(SimpleController::class, 'indexAction'));

		ob_start();
		ApplicationFactory::http()->run();
		$this->assertEquals('Hello world', ob_get_contents());
		ob_end_clean();
	}

	/**
	 * @test
	 */
	public function jsonControllerTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_URI', '/json?bar=baz');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'GET');
		Arrays::setValueByPath($_SERVER, 'CONTENT_TYPE', 'application/json');

		ApplicationFactory::http();

		/** @var RouterInterface $router */
		$router = ObjectManager::get(RouterInterface::class);
		$router->addRoute('/json', 'get', new ControllerRouteData(SimpleController::class, 'jsonAction'));

		ob_start();
		ApplicationFactory::http()->run();

		/** @var HttpApplicationResponseInterface $response */
		$response = ObjectManager::get(HttpApplicationResponseInterface::class);

		/** @var JsonResponse $parentResponse */
		$parentResponse = $response->getParentResponse();
		$this->assertEquals('application/json', $response->getParentResponse()->headers->get('CONTENT_TYPE'));
		$this->assertEquals('["Hello world"]', ob_get_contents());
		ob_end_clean();
	}
}
