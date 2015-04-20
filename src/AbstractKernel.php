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

	public function __construct() {
		Registry::add('CONTEXT', getenv('CONTEXT') ? : 'Development');
		Registry::addImplementation(ObjectManagerInterface::class, new ObjectManager());
	}
}
