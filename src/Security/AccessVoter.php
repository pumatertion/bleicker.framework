<?php

namespace Bleicker\Framework\Security;

use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Bleicker\Security\VoteInterface;
use Closure;

/**
 * Class AccessVoter
 *
 * @package Bleicker\Framework\Security
 */
class AccessVoter implements AccessVoterInterface {

	/**
	 * @var array
	 */
	public static $votes = [];

	/**
	 * @param VoteInterface $vote
	 * @return void
	 */
	public static function addVote(VoteInterface $vote) {
		static::$votes[] = $vote;
	}

	/**
	 * @param string $for
	 * @param callable $onAccessClosure
	 * @return boolean|mixed
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 */
	public static function vote($for, Closure $onAccessClosure = NULL) {
		$arguments = array_slice(func_get_args(), 2);
		$votes = static::getMatchingVotes($for);
		/** @var VoteInterface $vote */
		foreach ($votes as $vote) {
			$vote->vote($arguments);
		}
		if ($onAccessClosure !== NULL) {
			return $onAccessClosure();
		}
		return TRUE;
	}

	/**
	 * @param string $for
	 * @return array
	 */
	public static function getMatchingVotes($for) {
		return array_filter(static::$votes, static::votesWherePatternMacthingForFilter($for));
	}

	/**
	 * @param string $for
	 * @return callable
	 */
	protected static function votesWherePatternMacthingForFilter($for) {
		return function (VoteInterface $vote) use ($for) {
			return (boolean)preg_match('<' . addslashes($vote->getPattern()) . '>' . addslashes($vote->getModifier()), $for);
		};
	}

	/**
	 * @return void
	 */
	public static function prune() {
		static::$votes = [];
	}
}
