<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\ResponseInterface;

/**
 * Interface HttpApplicationResponseInterface
 *
 * @package Bleicker\Framework
 */
interface HttpApplicationResponseInterface extends ApplicationResponseInterface {

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
