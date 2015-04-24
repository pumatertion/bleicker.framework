<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Utility\Arrays;

/**
 * Class Registry
 *
 * @package Bleicker\Framework
 */
class Registry implements RegistryInterface {

	/**
	 * @var array
	 */
	protected static $storage = [];

	/**
	 * @param string $path
	 * @param mixed|null $value
	 * @return void
	 */
	public static function add($path, $value = NULL) {
		Arrays::setValueByPath(static::$storage, $path, $value);
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public static function get($path) {
		return Arrays::getValueByPath(static::$storage, $path);
	}

	/**
	 * @return void
	 */
	public static function prune() {
		static::$storage = [];
	}
}
