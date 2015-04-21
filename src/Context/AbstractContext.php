<?php

namespace Bleicker\Framework\Context;

/**
 * Class AbstractContext
 *
 * @package Bleicker\Framework\Context
 */
abstract class AbstractContext implements ContextInterface{

	const ENV_VAR = 'CONTEXT', PRODUCTION = 'production', DEVELOPMENT = FALSE;

	/**
	 * @return boolean
	 */
	public static function isDevelopment(){
		return getenv(static::ENV_VAR) === static::DEVELOPMENT;
	}

	/**
	 * @return boolean
	 */
	public static function isProduction() {
		return getenv(static::ENV_VAR) === static::PRODUCTION;
	}

}
