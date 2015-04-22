<?php

namespace Tests\Bleicker\Framework\Unit\Context;

use Bleicker\Framework\Context\Context;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ContextTest
 *
 * @package Tests\Bleicker\Framework\Unit\Context
 */
class ContextTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function contextIsTesting() {
		$this->assertTrue(Context::isTesting());
	}
}
