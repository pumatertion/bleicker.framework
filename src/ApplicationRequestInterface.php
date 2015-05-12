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
	 * @return HttpRequestInterface
	 */
	public function getMainRequest();

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
	 * @param array $contents
	 * @return $this
	 */
	public function setContents(array $contents = []);

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
