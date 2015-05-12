<?php

namespace Bleicker\Framework\Controller;

use Bleicker\Framework\Exception\RedirectException;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\HttpApplicationResponseInterface;
use Bleicker\Response\ResponseInterface as ApplicationResponseInterface;
use Bleicker\View\ViewInterface;

/**
 * Interface ControllerInterface
 *
 * @package Bleicker\Framework\Controller
 */
interface ControllerInterface {

	/**
	 * @param string $method
	 * @return $this
	 */
	public function resolveView($method);

	/**
	 * @param string $method
	 * @return $this
	 */
	public function resolveFormat($method);

	/**
	 * @return boolean
	 */
	public function hasView();

	/**
	 * @return ViewInterface
	 */
	public function getView();

	/**
	 * @param HttpApplicationRequestInterface $request
	 * @return $this
	 */
	public function setRequest(HttpApplicationRequestInterface $request);

	/**
	 * @return HttpApplicationRequestInterface
	 */
	public function getRequest();

	/**
	 * @param HttpApplicationResponseInterface $response
	 * @return $this
	 */
	public function setResponse(HttpApplicationResponseInterface $response);

	/**
	 * @return HttpApplicationResponseInterface
	 */
	public function getResponse();

	/**
	 * @param string $uri
	 * @param integer $statusCode
	 * @param string $statusMessage
	 * @throws RedirectException
	 */
	public function redirect($uri, $statusCode, $statusMessage);
}
