<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Http\Response;

/**
 * Interface HttpApplicationResponseInterface
 *
 * @package Bleicker\Framework
 */
interface HttpApplicationResponseInterface extends ApplicationResponseInterface {

	/**
	 * @return Response
	 */
	public function getParentResponse();

	/**
	 * @param Response $parentResponse
	 * @return $this
	 */
	public function setParentResponse(Response $parentResponse);
}
