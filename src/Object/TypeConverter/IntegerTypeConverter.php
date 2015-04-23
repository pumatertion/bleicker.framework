<?php

namespace Bleicker\Framework\Object\TypeConverter;

use Prophecy\Exception\Exception;

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
		if($source === NULL){
			return FALSE;
		}

		try {
			(integer)$source;
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
		return (integer)$source;
	}
}
