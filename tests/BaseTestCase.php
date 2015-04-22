<?php
namespace Tests\Bleicker\Framework;

/**
 * Class BaseTestCase
 *
 * @package Tests\Bleicker\Framework
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();
		putenv('CONTEXT=testing');
	}
}
