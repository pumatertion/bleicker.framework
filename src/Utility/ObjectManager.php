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
	 * @param $objectNameOrInterfaceName
	 * @param mixed $argument ...argument
	 * @return object
	 * @throws ExistingClassOrInterfaceNameExpectedException
	 * @throws ArgumentsGivenButImplementationIsAlreadyAnObjectException
	 */
	public static function get($objectNameOrInterfaceName, $argument = NULL) {
		if (!class_exists($objectNameOrInterfaceName)) {
			throw new ExistingClassOrInterfaceNameExpectedException('Class or Interface does not exist', 1429643755);
		}

		$implementation = static::getObjectFromRegistryImplementations($objectNameOrInterfaceName);

		if ($argument !== NULL && is_object($implementation) && !($implementation instanceof Closure)) {
			throw new ArgumentsGivenButImplementationIsAlreadyAnObjectException('Object already exists as implementation and can not have arguments', 1429683991);
		}

		if ($implementation instanceof Closure) {
			$arguments = array_slice(func_get_args(), 1);
			$object = call_user_func($implementation, $arguments);
			return $object;
		}

		if (is_object($implementation)) {
			return $implementation;
		}

		if ($argument !== NULL) {
			$arguments = array_slice(func_get_args(), 1);
			return static::getObjectWithContructorArguments($objectNameOrInterfaceName, $arguments);
		}

		return static::getObject($objectNameOrInterfaceName);
	}

	/**
	 * @param $objectNameOrInterfaceName
	 * @return mixed
	 */
	protected static function getObjectFromRegistryImplementations($objectNameOrInterfaceName) {
		return Registry::getImplementation($objectNameOrInterfaceName);
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
