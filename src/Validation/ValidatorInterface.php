<?php
namespace Bleicker\Framework\Validation;

/**
 * Class AbstractValidator
 *
 * @package Bleicker\Framework\Validation
 */
interface ValidatorInterface {

	/**
	 * @param mixed $source
	 * @return $this
	 */
	public function validate($source = NULL);

	/**
	 * @return ResultCollection
	 */
	public function getResults();

}