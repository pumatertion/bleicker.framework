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
	 * @return ResultInterface
	 */
	public function validate($source = NULL);

	/**
	 * @return static
	 */
	public static function create();
}