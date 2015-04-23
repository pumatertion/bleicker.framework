<?php

namespace Bleicker\Framework\Object\TypeConverter;

/**
 * Interface TypeConverterInterface
 *
 * @package Bleicker\Framework\Object\TypeConverter
 */
interface TypeConverterInterface {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType);

	/**
	 * @param mixed $source
	 * @return mixed
	 */
	public function convert($source);
}
