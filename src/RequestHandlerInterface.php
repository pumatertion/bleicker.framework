<?php

namespace Bleicker\Framework;

/**
 * Interface RequestHandlerInterface
 *
 * @package Bleicker\Framework
 */
interface RequestHandlerInterface {

	/**
	 * @return $this
	 */
	public function initialize();

	/**
	 * @return $this
	 */
	public function handle();
}
