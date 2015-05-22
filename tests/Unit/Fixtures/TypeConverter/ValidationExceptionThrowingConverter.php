<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\TypeConverter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Validation\ArrayValidator;
use Bleicker\Framework\Validation\ErrorInterface;
use Bleicker\Framework\Validation\Exception\ValidationException;
use Bleicker\Framework\Validation\MessageInterface;
use Bleicker\Framework\Validation\ResultsInterface;
use Bleicker\ObjectManager\ObjectManager;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\FailingTestValidator;
use Tests\Bleicker\Framework\Unit\Fixtures\Validation\SuccessTestValidator;

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
		$failingValidator = new FailingTestValidator();
		$successValidator = new SuccessTestValidator();
		$validationResults = ArrayValidator::create()
			->addValidatorForPropertyPath('foo.bar', $failingValidator)
			->addValidatorForPropertyPath('foo.bar', $successValidator)
			->validate($source)
			->getResults();

		if($validationResults->filter(function(MessageInterface $message){
			return $message->getSeverity() === MessageInterface::SEVERITY_ERROR;
		})->count() > 0){
			throw ValidationException::create($validationResults, 'Your data is invalid', 1431981824);
		}

		return $source;
	}
}
