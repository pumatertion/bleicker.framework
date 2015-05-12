<?php

namespace Bleicker\Framework;

use Bleicker\Response\AbstractResponse;
use Bleicker\Framework\Http\ResponseInterface;
/**
 * Class HttpApplicationResponse
 *
 * @package Bleicker\Framework
 */
class HttpApplicationResponse extends AbstractResponse implements ApplicationResponseInterface {

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
