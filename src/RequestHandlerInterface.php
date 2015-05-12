<?php

namespace Bleicker\Framework;

/**
 * Interface RequestHandlerInterface
 *
 * @package Bleicker\Framework
 */
interface RequestHandlerInterface {

	/**
	 * @return $this
	 */
	public function initialize();

	/**
	 * @return boolean
	 */
	public function isInitialized();

	/**
	 * @return ApplicationRequestInterface
	 */
	public function getApplicationRequest();

	/**
	 * @return ApplicationResponseInterface
	 */
	public function getApplicationResponse();

	/**
	 * @return $this
	 */
	public function run();
}
