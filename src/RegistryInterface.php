<?php

namespace Bleicker\Framework;

use Bleicker\Framework\Utility\Arrays;
use Bleicker\Security\VoteInterface;

/**
 * Class RegistryInterface
 *
 * @package Bleicker\Framework
 */
interface RegistryInterface {

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
	 * @return array
	 */
	public static function getAll();

	/**
	 * @return void
	 */
	public static function prune();
}
