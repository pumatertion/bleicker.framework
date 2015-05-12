<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\Response;
use Bleicker\Framework\Http\ResponseInterface;
use Bleicker\ObjectManager\ObjectManager;

/**
 * Class HttpApplication
 *
 * @package Bleicker\Framework
 */
class HttpApplication implements HttpApplicationInterface {

	/**
	 * @return void
	 */
	public function run() {
		/** @var HandlerInterface $httpHandler */
		$httpHandler = ObjectManager::get(HandlerInterface::class, Handler::class);
		$httpHandler->initialize()->handle();

		/** @var ResponseInterface $response */
		$response = ObjectManager::get(MainResponseInterface::class, Response::class);
		$response->send();
	}
}
