<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\RequestInterface as HttpRequestInterface;
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
	protected $arguments;

	/**
	 * @param HttpRequestInterface $parentRequest
	 */
	public function __construct(HttpRequestInterface $parentRequest = NULL) {
		parent::__construct($parentRequest);
		$this->parameters = [];
		$this->arguments = [];
		$this->headers = [];
	}

	/**
	 * @return HttpRequestInterface
	 */
	public function getParentRequest() {
		return parent::getParentRequest();
	}

	/**
	 * @return HttpRequestInterface
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
		return $this->arguments;
	}

	/**
	 * @param array $arguments
	 * @return $this
	 */
	public function setContents(array $arguments = []) {
		$this->arguments = $arguments;
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
