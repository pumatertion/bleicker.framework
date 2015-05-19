<?php

namespace Bleicker\Framework;

use Bleicker\ObjectManager\ObjectManager;

/**
 * Class HttpApplication
 *
 * @package Bleicker\Framework
 */
class HttpApplication implements HttpApplicationInterface {

	/**
	 * @var RequestHandlerInterface
	 */
	protected $requestHandler;

	public function __construct() {
		$this->requestHandler = ObjectManager::get(RequestHandlerInterface::class);
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->requestHandler->initialize()->run()->getApplicationResponse()->send();
	}
}
