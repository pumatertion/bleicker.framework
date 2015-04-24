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

	const REGISTRATION_PATH = 'implementations', SINGLETONS_PATH = 'singletons';

	/**
	 * @param $alias
	 * @param mixed $argument ...argument
	 * @return object
	 * @throws ExistingClassOrInterfaceNameExpectedException
	 * @throws ArgumentsGivenButImplementationIsAlreadyAnObjectException
	 */
	public static function get($alias, $argument = NULL) {

		$implementation = static::getImplementation($alias);

		if ($argument !== NULL && is_object($implementation) && !($implementation instanceof Closure)) {
			throw new ArgumentsGivenButImplementationIsAlreadyAnObjectException('Object already exists as implementation and can not have arguments', 1429683991);
		}

		if ($implementation instanceof Closure) {
			$arguments = array_slice(func_get_args(), 1);
			$object = call_user_func_array($implementation, $arguments);
			if (static::isSingleton($alias)) {
				static::register($alias, $object);
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
	 * @param string $alias
	 * @param string $implementation
	 * @return void
	 */
	public static function register($alias, $implementation) {
		$implementations = static::getImplementations();
		$implementations[$alias] = $implementation;
		Registry::add(static::REGISTRATION_PATH, $implementations);
	}

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function unregister($alias) {
		$implementations = static::getImplementations();
		if (array_key_exists($alias, $implementations)) {
			unset($implementations[$alias]);
		}
		Registry::add(static::REGISTRATION_PATH, $implementations);
	}

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function makeSingleton($alias) {
		$singletons = static::getSingletons();
		$singletons[$alias] = TRUE;
		Registry::add(static::SINGLETONS_PATH, $singletons);
	}

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function makePrototype($alias) {
		$singletons = static::getSingletons();
		unset($singletons[$alias]);
		Registry::add(static::SINGLETONS_PATH, $singletons);
	}

	/**
	 * @param $alias
	 * @return boolean
	 */
	public static function isSingleton($alias) {
		$singletons = static::getSingletons();
		return array_key_exists($alias, $singletons) ? $singletons[$alias] : FALSE;
	}

	/**
	 * @param $alias
	 * @return boolean
	 */
	public static function isPrototype($alias) {
		return !static::isSingleton($alias);
	}

	/**
	 * @return array
	 */
	protected static function getImplementations() {
		$implementations = Registry::get(static::REGISTRATION_PATH);
		if (is_array($implementations)) {
			return $implementations;
		}
		return [];
	}

	/**
	 * @return array
	 */
	protected static function getSingletons() {
		$singletons = Registry::get(static::SINGLETONS_PATH);
		if (is_array($singletons)) {
			return $singletons;
		}
		return [];
	}

	/**
	 * @param $alias
	 * @return mixed
	 */
	public static function getImplementation($alias) {
		$implementations = static::getImplementations();
		if (array_key_exists($alias, $implementations)) {
			return $implementations[$alias];
		}
		return NULL;
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

	/**
	 * @return void
	 */
	public static function prune() {
		Registry::add(static::SINGLETONS_PATH, []);
		Registry::add(static::REGISTRATION_PATH, []);
	}
}
