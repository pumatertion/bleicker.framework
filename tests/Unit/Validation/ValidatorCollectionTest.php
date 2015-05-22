<?php

namespace Tests\Bleicker\Framework\Unit\Validation;

use Bleicker\Framework\Validation\MessageInterface;
use Bleicker\Framework\Validation\ValidatorCollection;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\FailingTestValidator;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class PropertyValidator
 *
 * @package Tests\Bleicker\Framework\Unit\Validation
 */
class ValidatorCollectionTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function createTest() {
		$validatorCollection = ValidatorCollection::create();
		$this->assertInstanceOf(ValidatorCollection::class, $validatorCollection);
	}

	/**
	 * @test
	 */
	public function emptyValidatorsTest() {
		$validatorCollection = ValidatorCollection::create();
		$this->assertEquals([], $validatorCollection->getValidators());
	}

	/**
	 * @test
	 */
	public function addValidatorTest() {
		$validator = new FailingTestValidator();
		$validatorCollection = ValidatorCollection::create()->add($validator);
		$this->assertInstanceOf(FailingTestValidator::class, $validatorCollection->first());
		$this->assertEquals(1, $validatorCollection->count());
	}

	/**
	 * @test
	 */
	public function removeValidatorTest() {
		$validator = new FailingTestValidator();
		$validatorCollection = ValidatorCollection::create()->add($validator)->remove($validator);
		$this->assertEquals(0, $validatorCollection->count());
	}

	/**
	 * @test
	 */
	public function validateTest() {
		$propertyValidator1 = new FailingTestValidator();
		$propertyValidator2 = new FailingTestValidator();
		$propertyValidator3 = new FailingTestValidator();
		$validatorCollection = ValidatorCollection::create()->add($propertyValidator1)->add($propertyValidator2)->add($propertyValidator3);
		$results = $validatorCollection->validate('foo')->getResults();
		$this->assertInstanceOf(MessageInterface::class, $results->first());
		$this->assertInstanceOf(MessageInterface::class, $results->next());
		$this->assertInstanceOf(MessageInterface::class, $results->next());
	}
}
