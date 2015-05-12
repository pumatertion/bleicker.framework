<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\ResponseInterface;

/**
 * Interface HttpApplicationResponseInterface
 *
 * @package Bleicker\Framework
 */
interface HttpApplicationResponseInterface {

	/**
	 * @return string
	 */
	public function send();

	/**
	 * @return ResponseInterface
	 */
	public function getParentResponse();

	/**
	 * @param ResponseInterface $parentResponse
	 * @return $this
	 */
	public function setParentResponse(ResponseInterface $parentResponse);
}
