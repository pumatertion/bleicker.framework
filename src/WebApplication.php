<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\RequestFactory;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Request\HandlerInterface;
use Bleicker\Request\MainRequestInterface;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;
use Bleicker\Session\Session;
use Bleicker\Session\SessionInterface;
use Bleicker\Framework\Http\RequestInterface;

/**
 * Class WebApplication
 *
 * @package Bleicker\Framework
 */
class WebApplication extends AbstractKernel implements ApplicationInterface {

	/**
	 * @return void
	 */
	public function run() {
		/** @var HandlerInterface $httpHandler */
		$httpHandler = ObjectManager::get(HandlerInterface::class, Handler::class);
		$httpHandler->initialize()->handle();

		/** @var RequestInterface $httpRequest */
		$httpRequest = ObjectManager::get(MainRequestInterface::class, function () {
			$request = RequestFactory::getInstance();
			ObjectManager::add(MainRequestInterface::class, $request);
			return $request;
		});

		/** @var SessionInterface $session */
		$session = ObjectManager::get(SessionInterface::class, Session::class);
		$httpRequest->setSession($session);

		/** @var MainResponseInterface $response */
		$response = ObjectManager::get(MainResponseInterface::class, Response::class);
		$response->send();
	}
}
