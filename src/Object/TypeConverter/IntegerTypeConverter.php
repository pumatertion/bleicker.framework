<?php

namespace Bleicker\Framework\Object\TypeConverter;

use Bleicker\Framework\Object\ConverterInterface;

/**
 * Class IntegerTypeConverter
 *
 * @package Bleicker\Framework\Object\TypeConverter
 */
class IntegerTypeConverter implements TypeConverterInterface {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if ($source !== NULL && in_array($targetType, [ConverterInterface::INT, ConverterInterface::INTEGER]) && (integer)$source) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param mixed $source
	 * @return mixed
	 */
	public function convert($source) {
		return (integer)$source;
	}
}
