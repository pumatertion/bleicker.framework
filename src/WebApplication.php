<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Handler;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Request\HandlerInterface;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;

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

		/** @var MainResponseInterface $response */
		$response = ObjectManager::get(MainResponseInterface::class, Response::class);
		$response->send();
	}
}
