<?php

namespace Tests\Bleicker\Framework\Unit\Object\TypeConverter;
use Bleicker\Framework\Object\TypeConverter\FloatTypeConverter;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class FloatTypeConverterTest
 *
 * @package Tests\Bleicker\Framework\Unit\Object\TypeConverter
 */
class FloatTypeConverterTest extends UnitTestCase{

	/**
	 * @test
	 */
	public function canConvertTest() {
		$this->assertTrue(FloatTypeConverter::canConvert('123.45', 'float'), 'Can convert from string "123" to float');
		$this->assertTrue(FloatTypeConverter::canConvert('123.45', 'double'), 'Can convert from string "123" to double');
		$this->assertTrue(FloatTypeConverter::canConvert('123,45', 'float'), 'Can convert from "123" to float');
		$this->assertTrue(FloatTypeConverter::canConvert('123,45', 'double'), 'Can convert from "123" to double');
		$this->assertFalse(FloatTypeConverter::canConvert(NULL, 'float'), 'Can not convert from NULL to float');
		$this->assertFalse(FloatTypeConverter::canConvert(NULL, 'double'), 'Can not convert from NULL to double');
	}

	/**
	 * @test
	 */
	public function convertTest(){
		$converter = new FloatTypeConverter();
		$this->assertEquals(123.01, $converter->convert('123.01'), 'Converts 123.01');
		$this->assertEquals(-123.01, $converter->convert('-123.01'), 'Converts -123.01');
		$this->assertEquals(123, $converter->convert('123,01'), 'Converts 123');
		$this->assertEquals(123, $converter->convert('123.abc'), 'Converts 123.abc');
		$this->assertEquals(0, $converter->convert(FALSE), 'Converts FALSE');
		$this->assertEquals(1, $converter->convert(TRUE), 'Converts FALSE');

	}
}
