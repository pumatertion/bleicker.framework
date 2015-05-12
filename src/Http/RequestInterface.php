<?php
namespace Bleicker\Framework\Http;

use Exception;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class Request
 *
 * @package Bleicker\Framework\Http
 */
interface RequestInterface {

	/**
	 * @return Request
	 */
	public function getParentRequest();

	/**
	 * @return HeaderBag
	 */
	public function getHeaders();

	/**
	 * @param Request $parentRequest
	 * @return $this
	 */
	public function setParentRequest(Request $parentRequest);

	/**
	 * @return ParameterBag
	 */
	public function getParameter();

	/**
	 * @throws Exception
	 */
	public static function createFromGlobals();

	/**
	 * @return ParameterBag
	 */
	public function getArguments();
}