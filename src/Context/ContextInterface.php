<?php

namespace Bleicker\Framework\Context;

/**
 * Interface ContextInterface
 *
 * @package Bleicker\Framework\Context
 */
interface ContextInterface {

	/**
	 * @return boolean
	 */
	public static function isDevelopment();

	/**
	 * @return boolean
	 */
	public static function isProduction();

}
