<?php

namespace Tests\Bleicker\Framework\Unit\Validation;

use Bleicker\Framework\Validation\Error;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ValidatorTest
 *
 * @package Tests\Bleicker\Framework\Unit\Validation
 */
class ErrorTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function errorAsStringTest() {
		$errorAsString = (string)Error::create('Validation Error (%1s) Property "%2s" contains invalid email "%3s"', 1432035545)->setPropertyPath('foo.bar.baz')->setPropertyValue('info@foo.com');
		$this->assertEquals('Validation Error (1432035545) Property "foo.bar.baz" contains invalid email "info@foo.com"', $errorAsString);
	}
}
