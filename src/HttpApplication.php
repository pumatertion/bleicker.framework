<?php

namespace Bleicker\Framework;

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

	/**
	 * @param RequestHandlerInterface $requestHandler
	 */
	public function __construct(RequestHandlerInterface $requestHandler) {
		$this->requestHandler = $requestHandler;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->requestHandler->initialize()->run()->getApplicationResponse()->send();
	}
}
