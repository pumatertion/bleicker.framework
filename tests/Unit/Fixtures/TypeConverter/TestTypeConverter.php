<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter;

use Bleicker\Framework\Object\TypeConverter\TypeConverterInterface;

/**
 * Class TestTypeConverter
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures
 */
class TestTypeConverter implements TypeConverterInterface {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		return TRUE;
	}

	/**
	 * @param integer $source
	 * @return string
	 */
	public function convert($source) {
		return 'converted';
	}
}
