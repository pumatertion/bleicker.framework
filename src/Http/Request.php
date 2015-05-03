<?php

namespace Bleicker\Framework\Http;

use Bleicker\Request\RequestInterface;
use Bleicker\Framework\Http\RequestInterface as HttpRequestInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class Request
 *
 * @package Bleicker\Framework\Http
 */
class Request extends HttpRequest implements RequestInterface, HttpRequestInterface {

	/**
	 * @var RequestInterface
	 */
	protected $parentRequest;

	/**
	 * @return RequestInterface
	 */
	public function getParentRequest() {
		return $this->parentRequest;
	}

	/**
	 * @param RequestInterface $parentRequest
	 * @return $this
	 */
	public function setParentRequest(RequestInterface $parentRequest) {
		$this->parentRequest = $parentRequest;
		return $this;
	}

	/**
	 * @return RequestInterface
	 */
	public function getMainRequest() {
		$parentRequest = $this->getParentRequest();
		if ($parentRequest === NULL) {
			return $this;
		}
		if ($parentRequest->getParentRequest() instanceof RequestInterface) {
			return $parentRequest->getParentRequest();
		}
		return $parentRequest;
	}

	/**
	 * @return boolean
	 */
	public function isMainRequest() {
		return $this->getMainRequest() === $this;
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
