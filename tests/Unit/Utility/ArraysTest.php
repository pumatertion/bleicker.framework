<?php

namespace Tests\Bleicker\Framework\Unit\Utility;

use Bleicker\Framework\Utility\Arrays;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ArraysTest
 *
 * @package Tests\Bleicker\Framework\Unit\Utility
 */
class ArraysTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function setValueByPath() {
		$array = [];
		Arrays::setValueByPath($array, 'foo.bar', 'baz');
		$expected = ['foo' => ['bar' => 'baz']];
		$this->assertTrue($array == $expected);
		$this->assertEquals('baz', Arrays::getValueByPath($array, 'foo.bar'));
	}
}
