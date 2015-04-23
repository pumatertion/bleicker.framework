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
	protected $votes = [];

	/**
	 * @param VoteInterface $vote
	 * @return $this
	 */
	public function addVote(VoteInterface $vote) {
		$this->votes[] = $vote;
		return $this;
	}

	/**
	 * @param string $for
	 * @param callable $successCallback
	 * @return boolean|mixed
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 */
	public function vote($for, Closure $successCallback = NULL) {
		$votes = $this->getMatchingVotes($for);
		/** @var VoteInterface $vote */
		foreach($votes as $vote){
			$vote->vote();
		}
		if($successCallback !== NULL){
			$arguments = array_slice(func_get_args(), 2);
			return call_user_func_array($successCallback, $arguments);
		}
		return TRUE;
	}

	/**
	 * @param string $for
	 * @return array
	 */
	public function getMatchingVotes($for) {
		return array_filter($this->votes, $this->votesWherePatternMacthingForFilter($for));
	}

	/**
	 * @param string $for
	 * @return callable
	 */
	protected function votesWherePatternMacthingForFilter($for) {
		return function (VoteInterface $vote) use ($for) {
			return (boolean)preg_match('<' . addslashes($vote->getPattern()) . '>' . addslashes($vote->getModifier()), $for);
		};
	}
}
