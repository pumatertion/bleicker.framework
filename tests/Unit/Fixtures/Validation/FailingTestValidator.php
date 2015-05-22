<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\Validation;

use Bleicker\Framework\Validation\AbstractValidator;
use Bleicker\Framework\Validation\Message;

/**
 * Class FailingTestValidator
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures\Validation
 */
class FailingTestValidator extends AbstractValidator {

	/**
	 * @param string $source
	 * @return $this
	 */
	public function validate($source = NULL) {
		$result = Message::create(sprintf('Source "%1s" is invalid.', $source), 1432028399, $source);
		$this->getResults()->add($result);
		return $this;
	}
}
