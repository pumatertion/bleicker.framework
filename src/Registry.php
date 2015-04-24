<?php

namespace Bleicker\Framework;

/**
 * Class Registry
 *
 * @package Bleicker\Framework
 */
class Registry implements RegistryInterface {

	/**
	 * @var array
	 */
	public static $storage = [];

	/**
	 * @param string $path
	 * @param mixed|null $value
	 * @return void
	 */
	public static function add($path, $value = NULL) {
		static::$storage[$path] = $value;
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public static function get($path) {
		if (array_key_exists($path, static::$storage)) {
			return static::$storage[$path];
		}
		return NULL;
	}

	/**
	 * @return array
	 */
	public static function getAll() {
		return static::$storage;
	}

	/**
	 * @return void
	 */
	public static function prune() {
		static::$storage = [];
	}
}
