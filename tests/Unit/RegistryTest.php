<?php

namespace Tests\Bleicker\Framework\Unit;

use Bleicker\Framework\Registry;
use Closure;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClass;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class RegistryTest
 *
 * @package Tests\Bleicker\Framework\Unit
 */
class RegistryTest extends UnitTestCase {

	protected function tearDown() {
		parent::tearDown();
		Registry::prune();
	}

	/**
	 * @test
	 */
	public function registryAdd() {
		Registry::add('foo', 'bar');
		$this->assertEquals('bar', Registry::get('foo'));
	}
}
