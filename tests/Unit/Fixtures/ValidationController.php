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
	 * @param $passedArgument
	 * @return string
	 */
	public function validationErrorAction($passedArgument){
		return $passedArgument . '::' . (string)$this->getValidationException()->getResults()->first();
	}

	/**
	 * @throws ValidationException
	 */
	public function converterValidationAction() {
		try {
			Converter::convert(['foo' => ['bar' => 'baz']], 'whatever');
		} catch (ValidationException $exception) {
			$exception->setControllerName(static::class)->setMethodName('validationErrorAction')->setMethodArguments(['invoked by converter']);
			throw $exception;
		}
	}
}
