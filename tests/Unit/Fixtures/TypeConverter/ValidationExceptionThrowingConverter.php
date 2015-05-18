<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Validation\Exception\ValidationException;
use Bleicker\Framework\Validation\Result;
use Bleicker\Framework\Validation\ResultsInterface;
use Bleicker\ObjectManager\ObjectManager;

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
		$validationResults->add('foo.bar.baz', Result::create('Invalid input', 1431982690, ['foo' => 'bar']));
		throw ValidationException::create('Your data is invalid', 1431981824);
	}
}
