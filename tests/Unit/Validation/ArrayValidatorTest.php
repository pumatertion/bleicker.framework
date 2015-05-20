<?php

namespace Tests\Bleicker\Framework\Unit\Validation;

use Bleicker\Framework\Utility\Arrays;
use Bleicker\Framework\Validation\ArrayValidator;
use Bleicker\Framework\Validation\MessageInterface;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\FailingTestValidator;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\SuccessTestValidator;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ObjectValidatorTest
 *
 * @package Tests\Bleicker\Framework\Unit\Validation
 */
class ArrayValidatorTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function createTest() {
		$arrayValidator = ArrayValidator::create();
		$this->assertInstanceOf(ArrayValidator::class, $arrayValidator);
	}

	/**
	 * @test
	 */
	public function arrayValidationTest() {
		$array = [];
		Arrays::setValueByPath($array, 'foo.bar.baz', 'foo');
		$failingValidator = new FailingTestValidator();
		$successValidator = new SuccessTestValidator();
		$arrayValidator = ArrayValidator::create();
		$results = $arrayValidator->addValidatorForPropertyPath('foo.bar.baz', $failingValidator)->addValidatorForPropertyPath('foo.bar.baz', $successValidator)->validate($array)->getResults();
		$this->assertEquals(MessageInterface::SEVERITY_ERROR, $results->first()->getSeverity());
		$this->assertEquals(MessageInterface::SEVERITY_SUCCESS, $results->next()->getSeverity());
	}
}
