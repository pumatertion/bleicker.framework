<?php
namespace Bleicker\Framework;

use Bleicker\Framework\Http\Request;

/**
 * Class ApplicationRequest
 *
 * @package Bleicker\Framework
 */
interface ApplicationRequestInterface {

	/**
	 * @param Request $parentRequest
	 */
	public function __construct(Request $parentRequest = NULL);

	/**
	 * @return Request
	 */
	public function getMainRequest();

	/**
	 * @return Request
	 */
	public function getParentRequest();

	/**
	 * @param Request $parentRequest
	 * @return $this
	 */
	public function setParentRequest(Request $parentRequest);

	/**
	 * @return boolean
	 */
	public function isMainRequest();
}