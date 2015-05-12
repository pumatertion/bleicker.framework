<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\RequestInterface;
use Bleicker\Registry\Utility\Arrays;
use Bleicker\Request\AbstractRequest;

/**
 * Class ApplicationRequest
 *
 * @package Bleicker\Framework
 */
class HttpApplicationRequest extends AbstractRequest implements ApplicationRequestInterface {

	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var array
	 */
	protected $contents;

	/**
	 * @param RequestInterface $parentRequest
	 */
	public function __construct(RequestInterface $parentRequest = NULL) {
		parent::__construct($parentRequest);
		$this->parameters = [];
		$this->contents = [];
		$this->headers = [];
	}

	/**
	 * @return RequestInterface
	 */
	public function getParentRequest() {
		return parent::getParentRequest();
	}

	/**
	 * @return RequestInterface
	 */
	public function getMainRequest() {
		return parent::getMainRequest();
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
	public function setContents(array $contents = []) {
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
