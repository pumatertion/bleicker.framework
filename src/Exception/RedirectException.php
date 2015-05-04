<?php

namespace Bleicker\Framework\Exception;

use Exception;

/**
 * Class RedirectException
 *
 * @package Bleicker\Framework\Exception
 */
class RedirectException extends Exception {

	/**
	 * @var string
	 */
	protected $uri;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @param string $uri
	 * @param integer $status
	 * @param string $message
	 * @param integer $code
	 * @param Exception $previous
	 * @throws InvalidStatusException
	 */
	public function __construct($uri, $status, $message, $code = 0, Exception $previous = NULL) {
		if ($status < 300 || $status >= 400) {
			throw new InvalidStatusException('Invalid redirect status. Status has to be 3xx', 1430730268);
		}
		parent::__construct($message, $code, $previous);
		$this->uri = $uri;
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}
}
