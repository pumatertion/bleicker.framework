<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Utility\Arrays;

/**
 * Class RegistryInterface
 *
 * @package Bleicker\Framework
 */
interface RegistryInterface {

	const FACTORY_PATH = 'factories', IIMPLENTATION_PATH = 'implementations', PATHSEPERATOR = Arrays::PATHSEPARATOR;

	/**
	 * @param string $path
	 * @param mixed $value
	 * @return void
	 */
	public static function add($path, $value = NULL);

	/**
	 * @param string $path
	 * @return mixed
	 */
	public static function get($path);

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @param mixed $value
	 * @return void
	 */
	public static function addFactory($classNameOrInterfaceNameTheFactoryIsFor, $value = NULL);

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @return mixed
	 */
	public static function getFactory($classNameOrInterfaceNameTheFactoryIsFor);

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @param mixed $value
	 * @return void
	 */
	public static function addImplementation($interfaceNameTheImplementionIsFor, $value = NULL);

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @return mixed
	 */
	public static function getImplementation($interfaceNameTheImplementionIsFor);

	/**
	 * @return array
	 */
	public static function getAll();

	/**
	 * @return void
	 */
	public static function prune();
}
