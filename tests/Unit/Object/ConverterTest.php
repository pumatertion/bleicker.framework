<?php

namespace Tests\Bleicker\Framework\Unit\Object;

use Bleicker\Framework\Object\Converter;
use Bleicker\Framework\Object\TypeConverter\TypeConverterInterface;
use Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter\TestTypeConverter;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ConverterTest
 *
 * @package Tests\Bleicker\Framework\Unit\Object
 */
class ConverterTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function registerUnregisterTypeConverterTest() {
		$alias = TestTypeConverter::class;
		Converter::registerTypeConverter($alias, new TestTypeConverter());
		$this->assertInstanceOf(TypeConverterInterface::class, Converter::getTypeConverter($alias), 'TypeConverter is registered');
		Converter::unregisterTypeConverter($alias);
		$this->assertNull(Converter::getTypeConverter($alias), 'TypeConverter is not registered');
	}

	/**
	 * @test
	 */
	public function convertWithTestTypeConverter() {
		$alias = TestTypeConverter::class;
		Converter::registerTypeConverter($alias, new TestTypeConverter());
		$this->assertEquals('converted', Converter::convert('foo', 'bar'), 'Is converted to expected result');
	}
}
