<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\Validation;

use Bleicker\Framework\Validation\AbstractValidator;
use Bleicker\Framework\Validation\Error;
use Bleicker\Framework\Validation\ResultInterface;

/**
 * Class TestValidator
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures\Validation
 */
class TestValidator extends AbstractValidator {

	/**
	 * @param mixed $source
	 * @return ResultInterface
	 */
	public function validate($source = NULL) {
		return Error::create('Invalid email "%1s"', 1432028399, ['foo@bar.com']);
	}
}
