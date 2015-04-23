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

	/**
	 * @test
	 */
	public function baseTest() {
		$voter = new AccessVoter();
		$this->assertInstanceOf(AccessVoter::class, $voter);
	}

	/**
	 * @test
	 */
	public function accessVoterFindsOneMathingVote() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$notMathingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitlE()');

		$matchingVotes = $accessVoter
			->addVote($matchingVote)
			->addVote($notMathingVote)
			->getMatchingVotes(SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$this->assertEquals(1, count($matchingVotes));
	}

	/**
	 * @test
	 */
	public function accessVoterFindsTwoMathingVote() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$notMathingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitlE()', 'i');

		$matchingVotes = $accessVoter
			->addVote($matchingVote)
			->addVote($notMathingVote)
			->getMatchingVotes(SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$this->assertEquals(2, count($matchingVotes));
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\InvalidVoterExceptionException
	 */
	public function voteThrowsAccessGrantedException() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
			throw new Exception('Throwing an unknown Exception');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$accessVoter
			->addVote($matchingVote)
			->vote(SimpleClassHavingConstructorArgument::class . '::getTitle()');
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\AccessDeniedException
	 */
	public function voteThrowsAccessDeniedException() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
			throw new AccessDeniedException('Access denied because of foo');
		}, SimpleClassHavingConstructorArgument::class . '::getTitle()');

		$accessVoter
			->addVote($matchingVote)
			->vote(SimpleClassHavingConstructorArgument::class . '::getTitle()');
	}

	/**
	 * @test
	 */
	public function successCallbackTest() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
		});

		$result = $accessVoter
			->addVote($matchingVote)
			->vote('whatever', function () {
				return 'called';
			});

		$this->assertEquals('called', $result);
	}

	/**
	 * @test
	 */
	public function noSuccessCallbackTest() {
		$accessVoter = new AccessVoter();

		$matchingVote = new Vote(function () {
		});

		$result = $accessVoter
			->addVote($matchingVote)
			->vote('whatever');

		$this->assertEquals(TRUE, $result);
	}
}
