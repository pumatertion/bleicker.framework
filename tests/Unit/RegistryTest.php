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

	/**
	 * @test
	 */
	public function registryAddByPropertyPath() {
		Registry::add('foo.bar.baz', 'added');
		$this->assertEquals('added', Registry::get('foo.bar.baz'));
	}

	/**
	 * @test
	 */
	public function registryAddMultipleByPropertyPath() {
		Registry::add('foo.bar.baz', 'added1');
		Registry::add('bar.baz.foo', 'added2');
		$this->assertEquals('added1', Registry::get('foo.bar.baz'));
		$this->assertEquals('added2', Registry::get('bar.baz.foo'));
	}
}
