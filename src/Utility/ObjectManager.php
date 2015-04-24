<?php

namespace Bleicker\Framework\Utility;

use Bleicker\Framework\Exception\ArgumentsGivenButImplementationIsAlreadyAnObjectException;
use Bleicker\Framework\Exception\ExistingClassOrInterfaceNameExpectedException;
use Bleicker\Framework\Registry;
use Closure;

/**
 * Class ObjectManager
 *
 * @package Bleicker\Framework\Utility
 */
class ObjectManager implements ObjectManagerInterface {

	/**
	 * @param $alias
	 * @param mixed $argument ...argument
	 * @return object
	 * @throws ExistingClassOrInterfaceNameExpectedException
	 * @throws ArgumentsGivenButImplementationIsAlreadyAnObjectException
	 */
	public static function get($alias, $argument = NULL) {

		$implementation = static::getObjectFromRegistryImplementations($alias);

		if ($argument !== NULL && is_object($implementation) && !($implementation instanceof Closure)) {
			throw new ArgumentsGivenButImplementationIsAlreadyAnObjectException('Object already exists as implementation and can not have arguments', 1429683991);
		}

		if ($implementation instanceof Closure) {
			$arguments = array_slice(func_get_args(), 1);
			$object = call_user_func_array($implementation, $arguments);
			if (Registry::isSingletonImplementation($alias)) {
				Registry::addImplementation($alias, $object);
			}
			return $object;
		}

		if (is_object($implementation)) {
			return $implementation;
		}

		if ($argument !== NULL) {
			$arguments = array_slice(func_get_args(), 1);
			return static::getObjectWithContructorArguments($alias, $arguments);
		}

		return static::getObject($alias);
	}

	/**
	 * @param $alias
	 * @return mixed
	 */
	protected static function getObjectFromRegistryImplementations($alias) {
		return Registry::getImplementation($alias);
	}

	/**
	 * @param string $className
	 * @param array $arguments
	 * @return object
	 */
	protected static function getObjectWithContructorArguments($className, array $arguments) {
		$class = new \ReflectionClass($className);
		return $class->newInstanceArgs($arguments);
	}

	/**
	 * @param string $className
	 * @return object
	 */
	protected static function getObject($className) {
		return new $className();
	}
}
