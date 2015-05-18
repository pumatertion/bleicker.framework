<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures;

use Bleicker\Converter\Converter;
use Bleicker\Framework\Controller\AbstractController;
use Bleicker\Framework\Validation\Exception\ValidationException;

/**
 * Class ValidationController
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures
 */
class ValidationController extends AbstractController {

	/**
	 * @param string $passedArgument
	 * @return string
	 */
	public function editAction($passedArgument = NULL) {
		return (string)$passedArgument;
	}

	/**
	 * @throws ValidationException
	 */
	public function updateAction() {
		throw ValidationException::create('Your data is invalid', 1431981824)->setControllerName(static::class)->setMethodName('editAction')->setMethodArguments(['bar']);
	}

	/**
	 * @throws ValidationException
	 */
	public function converterValidationAction() {
		try {
			Converter::convert([], 'foo');
		} catch (ValidationException $exception) {
			$exception->setControllerName(static::class)->setMethodName('editAction')->setMethodArguments(['invoked by converter']);
			throw $exception;
		}
	}
}
