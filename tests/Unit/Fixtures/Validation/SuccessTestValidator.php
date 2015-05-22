<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures\Validation;

use Bleicker\Framework\Validation\AbstractValidator;
use Bleicker\Framework\Validation\Message;
use Bleicker\Framework\Validation\MessageInterface;

/**
 * Class SuccessValidator
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures\Validation
 */
class SuccessTestValidator extends AbstractValidator {

	/**
	 * @param string $source
	 * @return $this
	 */
	public function validate($source = NULL) {
		$result = Message::create(sprintf('Source "%1s" is invalid.', $source), 1432028399, $source, MessageInterface::SEVERITY_SUCCESS);
		$this->getResults()->add($result);
		return $this;
	}
}
