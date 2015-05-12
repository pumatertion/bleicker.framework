<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Response;

/**
 * Class HttpApplicationResponse
 *
 * @package Bleicker\Framework
 */
class HttpApplicationResponse implements HttpApplicationResponseInterface {

	/**
	 * @var Response
	 */
	protected $parentResponse;

	/**
	 * @param Response $parentResponse
	 */
	public function __construct(Response $parentResponse) {
		$this->parentResponse = $parentResponse;
	}

	/**
	 * @return Response
	 */
	public function getParentResponse() {
		return $this->parentResponse;
	}

	/**
	 * @param Response $parentResponse
	 * @return $this
	 */
	public function setParentResponse(Response $parentResponse) {
		$this->parentResponse = $parentResponse;
		return $this;
	}

	/**
	 * @return string
	 */
	public function send() {
		$this->getParentResponse()->send();
	}
}
