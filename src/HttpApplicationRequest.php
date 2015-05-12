<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Request;
use Bleicker\Registry\Utility\Arrays;

/**
 * Class HttpApplicationRequest
 *
 * @package Bleicker\Framework
 */
class HttpApplicationRequest implements HttpApplicationRequestInterface {

	/**
	 * @var array
	 */
	protected $headers = [];

	/**
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * @var array
	 */
	protected $contents;

	/**
	 * @var Request
	 */
	protected $parentRequest;

	/**
	 * @param Request $parentRequest
	 */
	public function __construct(Request $parentRequest) {
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
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters = []) {
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getParameter($path) {
		$subject = $this->getParameters();
		return Arrays::getValueByPath($subject, $path);
	}

	/**
	 * @return array
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * @param array $contents
	 * @return $this
	 */
	public function setContents(array $contents = NULL) {
		$this->contents = $contents;
		return $this;
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getContent($path) {
		$subject = $this->getContents();
		return Arrays::getValueByPath($subject, $path);
	}

	/**
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @param array $headers
	 * @return $this
	 */
	public function setHeaders(array $headers = []) {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getHeader($path) {
		$subject = $this->getHeaders();
		return Arrays::getValueByPath($subject, $path);
	}
}
