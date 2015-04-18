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
use Bleicker\View\ViewResolver;
use Bleicker\View\ViewResolverInterface;
use TYPO3\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3\Fluid\Core\Cache\SimpleFileCache;

/**
 * Class WebApplication
 *
 * @package Bleicker\Framework
 */
class WebApplication extends AbstractKernel implements ApplicationInterface {

	protected function __construct() {
		parent::__construct();
		static::getRegistry()->addImplementation(MainRequestInterface::class, Request::createFromGlobals());
		static::getRegistry()->addImplementation(MainResponseInterface::class, new Response());
		static::getRegistry()->addImplementation(RouterInterface::class, Router::getInstance());
		static::getRegistry()->addImplementation(ViewResolverInterface::class, new ViewResolver());
		static::getRegistry()->addImplementation(HandlerInterface::class, new Handler());
		static::getRegistry()->addImplementation(FluidCacheInterface::class, new SimpleFileCache(ROOT_DIRECTORY . '/Cache'));
	}

	/**
	 * @return void
	 */
	public function run() {
		/** @var Handler $httpHandler */
		$httpHandler = static::getRegistry()->getImplementation(HandlerInterface::class);
		$httpHandler->initialize()->handle();

		/** @var Response $response */
		$response = static::getRegistry()->getImplementation(MainResponseInterface::class);
		$response->send();
	}
}
