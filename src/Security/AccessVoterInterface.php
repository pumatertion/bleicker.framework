<?php

namespace Bleicker\Framework\Security;

use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Bleicker\Security\VoteInterface;
use Closure;

/**
 * Interface AccessVoterInterface
 *
 * @package Bleicker\Framework\Security
 */
interface AccessVoterInterface {

	/**
	 * @param VoteInterface $vote
	 * @return void
	 */
	public static function addVote(VoteInterface $vote);

	/**
	 * @param string $for
	 * @param Closure $onAccessClosure
	 * @return boolean|mixed
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 */
	public static function vote($for, Closure $onAccessClosure = NULL);

	/**
	 * @return void
	 */
	public static function prune();
}
