<?php

namespace Bleicker\Framework\Security;

use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Closure;

/**
 * Interface AccessVoterInterface
 *
 * @package Bleicker\Framework\Security
 */
interface AccessVoterInterface {

	/**
	 * @param string $for
	 * @param Closure $onAccessClosure
	 * @return boolean|mixed
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 */
	public function vote($for, Closure $onAccessClosure = NULL);
}
