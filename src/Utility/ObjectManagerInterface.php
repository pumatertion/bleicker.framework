<?php
namespace Bleicker\Framework\Utility;

/**
 * Interface ObjectManager
 *
 * @package Bleicker\Framework\Utility
 */
interface ObjectManagerInterface {

	/**
	 * @param $objectNameOrInterfaceName
	 * @param $argument ...$argument
	 */
	public function get($objectNameOrInterfaceName, $argument);
}