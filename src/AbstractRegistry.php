<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Exception\InvalidArgumentException;

/**
 * Class AbstractRegistry
 *
 * @package Bleicker\Framework
 * @todo Add Support for paths seperated by "."
 */
abstract class AbstractRegistry implements RegistryInterface {

	/**
	 * @var array
	 */
	protected static $storage = [self::IIMPLENTATION_PATH => [], self::FACTORY_PATH => []];

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
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @param mixed|null $value
	 * @return void
	 */
	public static function addFactory($classNameOrInterfaceNameTheFactoryIsFor, $value = NULL) {
		static::$storage[static::FACTORY_PATH][$classNameOrInterfaceNameTheFactoryIsFor] = $value;
	}

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @return mixed
	 */
	public static function getFactory($classNameOrInterfaceNameTheFactoryIsFor) {
		if (array_key_exists($classNameOrInterfaceNameTheFactoryIsFor, static::$storage[static::FACTORY_PATH])) {
			return static::$storage[static::FACTORY_PATH][$classNameOrInterfaceNameTheFactoryIsFor];
		}
		return NULL;
	}

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @param mixed|null $value
	 * @throws InvalidArgumentException
	 */
	public static function addImplementation($interfaceNameTheImplementionIsFor, $value = NULL) {
		if ($value !== NULL && !is_object($value)) {
			throw new InvalidArgumentException('Argument $value has to be an object or closure', 1429688409);
		}
		static::$storage[static::IIMPLENTATION_PATH][$interfaceNameTheImplementionIsFor] = $value;
	}

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @return mixed
	 */
	public static function getImplementation($interfaceNameTheImplementionIsFor) {
		if (array_key_exists($interfaceNameTheImplementionIsFor, static::$storage[static::IIMPLENTATION_PATH])) {
			return static::$storage[static::IIMPLENTATION_PATH][$interfaceNameTheImplementionIsFor];
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
		static::$storage = [self::IIMPLENTATION_PATH => [], self::FACTORY_PATH => []];
	}
}
