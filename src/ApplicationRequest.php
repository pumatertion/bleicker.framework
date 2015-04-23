<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Request;
use Bleicker\Request\RequestInterface;

/**
 * Class ApplicationRequest
 *
 * @package Bleicker\Framework
 */
class ApplicationRequest implements ApplicationRequestInterface {

	/**
	 * @var Request
	 */
	protected $parentRequest;

	/**
	 * @param Request $parentRequest
	 */
	public function __construct(Request $parentRequest = NULL) {
		$this->parentRequest = $parentRequest;
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
	 * @return Request
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
}