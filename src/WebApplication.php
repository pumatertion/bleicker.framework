<?php

namespace Bleicker\Framework;

use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Security\AccessVoter;
use Bleicker\Framework\Security\AccessVoterInterface;
use Bleicker\Framework\Utility\ObjectManager;
use Bleicker\Request\HandlerInterface;
use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\Request;
use Bleicker\Request\MainRequestInterface;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;
use Bleicker\Routing\RouterInterface;

/**
 * Class WebApplication
 *
 * @package Bleicker\Framework
 */
class WebApplication extends AbstractKernel implements ApplicationInterface {

	public function __construct() {
		Registry::addImplementation(MainRequestInterface::class, Request::createFromGlobals());
		Registry::addImplementation(MainResponseInterface::class, new Response());
		Registry::addImplementation(RouterInterface::class, Router::getInstance(ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR . 'route.cache.php', Context::isProduction() ? FALSE : TRUE));
		Registry::addImplementation(AccessVoterInterface::class, new AccessVoter());
		Registry::addImplementation(HandlerInterface::class, new Handler());
	}

	/**
	 * @return void
	 */
	public function run() {
		/** @var Handler $httpHandler */
		$httpHandler = ObjectManager::get(HandlerInterface::class);
		$httpHandler->initialize()->handle();

		/** @var Response $response */
		$response = ObjectManager::get(MainResponseInterface::class);
		$response->send();
	}
}
