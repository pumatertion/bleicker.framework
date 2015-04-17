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
	protected $storage;

	public function __construct() {
		$this->storage = [];
	}

	/**
	 * @param string $path
	 * @param mixed $value
	 * @return $this
	 */
	public function add($path, $value = NULL) {
		Arrays::setValueByPath($this->storage, $path, $value);
		return $this;
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function get($path) {
		return Arrays::getValueByPath($this->storage, $path);
	}

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @param mixed $value
	 * @return $this
	 */
	public function addFactory($classNameOrInterfaceNameTheFactoryIsFor, $value) {
		$this->add(self::FACTORY_PATH . self::PATHSEPERATOR . $classNameOrInterfaceNameTheFactoryIsFor, $value);
		return $this;
	}

	/**
	 * @param string $classNameOrInterfaceNameTheFactoryIsFor
	 * @return mixed
	 */
	public function getFactory($classNameOrInterfaceNameTheFactoryIsFor) {
		return $this->get(self::FACTORY_PATH . self::PATHSEPERATOR . $classNameOrInterfaceNameTheFactoryIsFor);
	}

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @param mixed $value
	 * @return $this
	 */
	public function addImplementation($interfaceNameTheImplementionIsFor, $value) {
		$this->add(self::IIMPLENTATION_PATH . self::PATHSEPERATOR . $interfaceNameTheImplementionIsFor, $value);
		return $this;
	}

	/**
	 * @param string $interfaceNameTheImplementionIsFor
	 * @return mixed
	 */
	public function getImplementation($interfaceNameTheImplementionIsFor) {
		return $this->get(self::IIMPLENTATION_PATH . self::PATHSEPERATOR . $interfaceNameTheImplementionIsFor);
	}

	/**
	 * @return array
	 */
	public function getStorage() {
		return $this->storage;
	}

}
