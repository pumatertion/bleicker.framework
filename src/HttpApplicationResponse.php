<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Response;
use Bleicker\Framework\Http\JsonResponse;
use Bleicker\Framework\Http\ResponseInterface;

/**
 * Class HttpApplicationResponse
 *
 * @package Bleicker\Framework
 */
class HttpApplicationResponse implements HttpApplicationResponseInterface {

	/**
	 * @var ResponseInterface
	 */
	protected $parentResponse;

	/**
	 * @param ResponseInterface $parentResponse
	 */
	public function __construct(ResponseInterface $parentResponse) {
		$this->parentResponse = $parentResponse;
	}

	/**
	 * @return ResponseInterface
	 */
	public function getParentResponse() {
		return $this->parentResponse;
	}

	/**
	 * @param ResponseInterface $parentResponse
	 * @return $this
	 */
	public function setParentResponse(ResponseInterface $parentResponse) {
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
