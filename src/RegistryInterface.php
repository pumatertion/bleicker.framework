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
	 * @return $this
	 */
	public function add($path, $value = NULL);

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function get($path);

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @param mixed $value
	 * @return $this
	 */
	public function addFactory($classNameOrInterfaceNameTheFactoryIsFor, $value);

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @return mixed
	 */
	public function getFactory($classNameOrInterfaceNameTheFactoryIsFor);

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @param mixed $value
	 * @return $this
	 */
	public function addImplementation($interfaceNameTheImplementionIsFor, $value);

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @return mixed
	 */
	public function getImplementation($interfaceNameTheImplementionIsFor);

	/**
	 * @return array
	 */
	public function getStorage();
}
