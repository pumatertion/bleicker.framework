<?php

namespace Bleicker\Framework\Object\TypeConverter;

use Bleicker\Framework\Object\ConverterInterface;

/**
 * Class FloatTypeConverter
 *
 * @package Bleicker\Framework\Object\TypeConverter
 */
class FloatTypeConverter implements TypeConverterInterface {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if ($source !== NULL && in_array($targetType, [ConverterInterface::FLOAT, ConverterInterface::DOUBLE]) && (float)$source) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param mixed $source
	 * @return mixed
	 */
	public function convert($source) {
		return (float)$source;
	}
}
