<?php

namespace Bleicker\Framework;

use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Security\AccessVoter;
use Bleicker\Framework\Security\AccessVoterInterface;
use Bleicker\Framework\Utility\ObjectManager;
use Bleicker\Request\HandlerInterface;
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
		ObjectManager::register(MainRequestInterface::class, Request::createFromGlobals());
		ObjectManager::register(MainResponseInterface::class, new Response());
		ObjectManager::register(RouterInterface::class, Router::getInstance(ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR . 'route.cache.php', Context::isProduction() ? FALSE : TRUE));
		ObjectManager::register(AccessVoterInterface::class, new AccessVoter());
		ObjectManager::register(HandlerInterface::class, new Handler());
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
