<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\ResponseInterface as HttpResponseInterface;
use Bleicker\Response\ResponseInterface;

/**
 * Interface ApplicationResponseInterface
 *
 * @package Bleicker\Framework
 */
interface ApplicationResponseInterface extends ResponseInterface {

	/**
	 * @return string
	 */
	public function send();

	/**
	 * @return HttpResponseInterface
	 */
	public function getParentResponse();

	/**
	 * @return HttpResponseInterface
	 */
	public function getMainResponse();

	/**
	 * @return boolean
	 */
	public function isMainResponse();
}
