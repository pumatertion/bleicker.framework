<?php

namespace Bleicker\Framework;

use Bleicker\FastRouter\Router;
use Bleicker\Request\HandlerInterface;
use Bleicker\Request\Http\Handler;
use Bleicker\Request\Http\Request;
use Bleicker\Request\MainRequestInterface;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;
use Bleicker\Routing\RouterInterface;
use TYPO3\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3\Fluid\Core\Cache\SimpleFileCache;

/**
 * Class WebApplication
 *
 * @package Bleicker\Framework
 */
class WebApplication extends AbstractKernel implements ApplicationInterface {

	public function __construct() {
		parent::__construct();
		Registry::addImplementation(MainRequestInterface::class, Request::createFromGlobals());
		Registry::addImplementation(MainResponseInterface::class, new Response());
		Registry::addImplementation(RouterInterface::class, Router::getInstance());
		Registry::addImplementation(MimeTypeExtensionGuesserInterface::class, new MimeTypeExtensionGuesser());
		Registry::addImplementation(HandlerInterface::class, new Handler());
		Registry::addImplementation(FluidCacheInterface::class, new SimpleFileCache(ROOT_DIRECTORY . '/Cache'));
	}

	/**
	 * @return void
	 */
	public function run() {
		/** @var Handler $httpHandler */
		$httpHandler = Registry::getImplementation(HandlerInterface::class);
		$httpHandler->initialize()->handle();

		/** @var Response $response */
		$response = Registry::getImplementation(MainResponseInterface::class);
		$response->send();
	}
}
