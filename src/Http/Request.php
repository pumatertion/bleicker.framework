<?php

namespace Bleicker\Framework\Http;

use Exception;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class Request
 *
 * @package Bleicker\Framework\Http
 */
class Request extends HttpRequest implements RequestInterface {

	/**
	 * @var Request
	 */
	protected $parentRequest;

	/**
	 * @throws Exception
	 */
	public static function createFromGlobals() {
		throw new Exception('Not supported. Please use a factory for creation', 1430670351);
	}

	/**
	 * @return Request
	 */
	public function getParentRequest() {
		return $this->parentRequest;
	}

	/**
	 * @param Request $parentRequest
	 * @return $this
	 */
	public function setParentRequest(Request $parentRequest) {
		$this->parentRequest = $parentRequest;
		return $this;
	}

	/**
	 * @return HeaderBag
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @return ParameterBag
	 */
	public function getParameter() {
		return $this->query;
	}

	/**
	 * @return ParameterBag
	 */
	public function getArguments() {
		return $this->request;
	}
}
