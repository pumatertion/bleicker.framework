<?php
namespace Bleicker\Framework;

use Bleicker\Framework\Utility\ObjectManagerInterface;

/**
 * Class Kernel
 *
 * @package Bleicker\Framework\Factory
 */
interface KernelInterface {

	/**
	 * @return RegistryInterface
	 */
	public static function getRegistry();

	/**
	 * @return ObjectManagerInterface
	 */
	public static function getObjectManager();

	/**
	 * @return KernelInterface
	 */
	public static function getInstance();

	/**
	 * @return void
	 */
	public function run();
}