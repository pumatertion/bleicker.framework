<?php

namespace Tests\Bleicker\Framework\Unit\Object\TypeConverter;
use Bleicker\Framework\Object\TypeConverter\StringTypeConverter;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class StringTypeConverterTest
 *
 * @package Tests\Bleicker\Framework\Unit\Object\TypeConverter
 */
class StringTypeConverterTest extends UnitTestCase{

	/**
	 * @test
	 */
	public function canConvertTest() {
		$this->assertTrue(StringTypeConverter::canConvert(123, 'string'), 'Can convert from string 123');
		$this->assertFalse(StringTypeConverter::canConvert(NULL, 'string'), 'Can not convert from NULL');
	}

	/**
	 * @test
	 */
	public function convertTest(){
		$converter = new StringTypeConverter();
		$this->assertEquals('123', $converter->convert(123), 'Converts 123');
		$this->assertEquals('-123', $converter->convert(-123), 'Converts -123');
		$this->assertEquals('-0.34', $converter->convert(-0.34), 'Converts "-0.34"');
	}
}
