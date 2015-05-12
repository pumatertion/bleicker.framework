<?php
namespace Bleicker\Framework\Http;

/**
 * Class Response
 *
 * @package Bleicker\Framework\Http
 */
interface ResponseInterface {

	/**
	 * Sends HTTP headers and content.
	 *
	 * @return ResponseInterface
	 */
	public function send();
}