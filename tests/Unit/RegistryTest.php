<?php

namespace Tests\Bleicker\Framework\Unit;

use Bleicker\Framework\Registry;
use Closure;
use Tests\Bleicker\Framework\BaseTestCase;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClass;

/**
 * Class RegistryTest
 *
 * @package Tests\Bleicker\Framework\Unit
 */
class RegistryTest extends BaseTestCase {

	protected function tearDown() {
		parent::tearDown();
		Registry::prune();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Framework\Exception\InvalidArgumentException
	 */
	public function addingSimpleTypeToImplementationThrowsException() {
		Registry::addImplementation(SimpleClass::class, 'foo');
	}

	/**
	 * @test
	 */
	public function registryAddClassImplementation() {
		Registry::addImplementation(SimpleClass::class, new SimpleClass());
		$this->assertInstanceOf(SimpleClass::class, Registry::getImplementation(SimpleClass::class));
	}

	/**
	 * @test
	 */
	public function registryAddClosureImplementation() {
		Registry::addImplementation(SimpleClass::class, function () {
			return new SimpleClass();
		});
		$this->assertInstanceOf(Closure::class, Registry::getImplementation(SimpleClass::class));
	}

	/**
	 * @test
	 */
	public function registryImplementationIsNullable() {
		Registry::addImplementation(SimpleClass::class, function () {
			return new SimpleClass();
		});
		Registry::addImplementation(SimpleClass::class, NULL);
		$this->assertNull(Registry::getImplementation(SimpleClass::class));
	}

	/**
	 * @test
	 */
	public function registryAdd() {
		Registry::add('foo', 'bar');
		$this->assertEquals('bar', Registry::get('foo'));
	}
}
