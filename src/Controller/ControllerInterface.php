<?php

namespace Bleicker\Framework\Controller;

use Bleicker\Framework\ApplicationRequestInterface;
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
	 * @param ApplicationRequestInterface $request
	 * @return $this
	 */
	public function setRequest(ApplicationRequestInterface $request);

	/**
	 * @return ApplicationRequestInterface
	 */
	public function getRequest();

	/**
	 * @param ApplicationResponseInterface $response
	 * @return $this
	 */
	public function setResponse(ApplicationResponseInterface $response);

	/**
	 * @return ApplicationResponseInterface
	 */
	public function getResponse();
}
