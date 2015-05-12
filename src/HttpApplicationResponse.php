<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\ResponseInterface;
use Bleicker\Response\AbstractResponse;

/**
 * Class HttpApplicationResponse
 *
 * @package Bleicker\Framework
 */
class HttpApplicationResponse extends AbstractResponse implements HttpApplicationResponseInterface {

	/**
	 * @return string
	 */
	public function send() {
		$this->getMainResponse()->send();
	}

	/**
	 * @return ResponseInterface
	 */
	public function getParentResponse() {
		return parent::getParentResponse();
	}

	/**
	 * @return ResponseInterface
	 */
	public function getMainResponse() {
		return parent::getMainResponse();
	}
}
