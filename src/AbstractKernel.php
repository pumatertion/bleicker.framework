<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Utility\ObjectManager;
use Bleicker\Framework\Utility\ObjectManagerInterface;

/**
 * Class Kernel
 *
 * @package Bleicker\Framework
 */
abstract class AbstractKernel implements KernelInterface {

	/**
	 * @var KernelInterface
	 */
	protected static $instance;

	/**
	 * @var Registry
	 */
	protected static $registry;

	private final function __clone() {
	}

	protected function __construct() {
		self::$registry = new Registry();
		self::$registry->addImplementation(ObjectManagerInterface::class, new ObjectManager());
	}

	/**
	 * @return KernelInterface
	 */
	public static final function getInstance() {
		if (NULL === self::$instance) {
			self::$instance = new static;
		}
		return self::$instance;
	}

	/**
	 * @return RegistryInterface
	 */
	public static function getRegistry() {
		return self::$registry;
	}

	/**
	 * @return ObjectManagerInterface
	 */
	public static function getObjectManager() {
		return self::$registry->getImplementation(ObjectManagerInterface::class);
	}
}
