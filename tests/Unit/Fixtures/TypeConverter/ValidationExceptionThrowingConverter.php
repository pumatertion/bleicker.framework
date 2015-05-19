<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Validation\ErrorInterface;
use Bleicker\Framework\Validation\Exception\ValidationException;
use Bleicker\Framework\Validation\ResultsInterface;
use Bleicker\ObjectManager\ObjectManager;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\TestValidator;

/**
 * Class ValidationExceptionThrowingConverter
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter
 */
class ValidationExceptionThrowingConverter extends AbstractTypeConverter {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		return TRUE;
	}

	/**
	 * @param mixed $source
	 * @return mixed
	 * @throws ValidationException
	 */
	public function convert($source) {
		/** @var ResultsInterface $validationResults */
		$validationResults = ObjectManager::get(ResultsInterface::class);
		$validationResult = TestValidator::create()->validate('foo');

		if ($validationResult instanceof ErrorInterface) {
			$validationResults->add('foo.bar.baz', $validationResult);
		}

		throw ValidationException::create('Your data is invalid', 1431981824);
	}
}
