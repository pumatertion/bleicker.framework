<?php

namespace Bleicker\Framework\Object\TypeConverter;

use Prophecy\Exception\Exception;

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
		if ($source === NULL) {
			return FALSE;
		}

		try {
			(float)$source;
			return TRUE;
		} catch (Exception $exception) {
			return FALSE;
		}
	}

	/**
	 * @param mixed $source
	 * @return mixed
	 */
	public function convert($source) {
		return (float)$source;
	}
}
