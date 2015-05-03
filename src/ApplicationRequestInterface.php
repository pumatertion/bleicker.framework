<?php
namespace Bleicker\Framework;

use Bleicker\Framework\Http\RequestInterface as HttpRequestInterface;
use Bleicker\Request\RequestInterface;

/**
 * Class ApplicationRequest
 *
 * @package Bleicker\Framework
 */
interface ApplicationRequestInterface extends RequestInterface {

	/**
	 * @return HttpRequestInterface
	 */
	public function getParentRequest();

	/**
	 * @param RequestInterface $parentRequest
	 * @return $this
	 */
	public function setParentRequest(RequestInterface $parentRequest);

	/**
	 * @return HttpRequestInterface
	 */
	public function getMainRequest();

	/**
	 * @return boolean
	 */
	public function isMainRequest();

	/**
	 * @return array
	 */
	public function getParameters();

	/**
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters = []);

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getParameter($path);

	/**
	 * @return array
	 */
	public function getContents();

	/**
	 * @param array $arguments
	 * @return $this
	 */
	public function setContents(array $arguments = []);

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getContent($path);

	/**
	 * @return array
	 */
	public function getHeaders();

	/**
	 * @param array $headers
	 * @return $this
	 */
	public function setHeaders(array $headers = []);

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getHeader($path);
}
