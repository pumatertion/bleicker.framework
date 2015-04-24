<?php
namespace Bleicker\Framework\Utility;

/**
 * Interface ObjectManager
 *
 * @package Bleicker\Framework\Utility
 */
interface ObjectManagerInterface {

	/**
	 * @param $alias
	 * @param $argument ...$argument
	 */
	public static function get($alias, $argument = NULL);
}