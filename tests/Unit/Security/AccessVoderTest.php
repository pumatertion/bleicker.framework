<?php

namespace Tests\Bleicker\Framework\Unit\Security;

use Bleicker\Framework\Security\AccessVoter;
use Bleicker\Security\Exception\AccessDeniedException;
use Bleicker\Security\Vote;
use Exception;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClassHavingConstructorArgument;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class AccessVoderTest
 *
 * @package Tests\Bleicker\Framework\Unit\Security
 */
class AccessVoderTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		AccessVoter::prune();
	}

	/**
	 * @test
	 */
	public function accessVoterFindsOneMathingVote() {
		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$notMatchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitlE()');

		AccessVoter::addVote($matchingVote);
		AccessVoter::addVote($notMatchingVote);

		$matchingVotes = AccessVoter::getMatchingVotes(SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$this->assertEquals(1, count($matchingVotes));
	}

	/**
	 * @test
	 */
	public function accessVoterFindsTwoMathingVote() {
		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$notMatchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitlE()', 'i');

		AccessVoter::addVote($matchingVote);
		AccessVoter::addVote($notMatchingVote);

		$matchingVotes = AccessVoter::getMatchingVotes(SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$this->assertEquals(2, count($matchingVotes));
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\InvalidVoterExceptionException
	 */
	public function voteThrowsInvalidVoterExceptionExceptionException() {
		$matchingVote = new Vote(function () {
			throw new Exception('Throwing an unknown Exception');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		AccessVoter::addVote($matchingVote);
		AccessVoter::vote(SimpleClassHavingConstructorArgument::class . '::getTitle()');
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\AccessDeniedException
	 */
	public function voteThrowsAccessDeniedException() {
		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		AccessVoter::addVote($matchingVote);
		AccessVoter::vote(SimpleClassHavingConstructorArgument::class . '::getTitle()');
	}

	/**
	 * @test
	 */
	public function successCallbackTest() {
		$matchingVote = new Vote(function () {
		});

		AccessVoter::addVote($matchingVote);

		$result = AccessVoter::vote('whatever', function () {
				return 'called';
			});

		$this->assertEquals('called', $result);
	}

	/**
	 * @test
	 */
	public function noSuccessCallbackTest() {
		$matchingVote = new Vote(function () {
		});

		AccessVoter::addVote($matchingVote);

		$result = AccessVoter::vote('whatever');

		$this->assertEquals(TRUE, $result);
	}
}
