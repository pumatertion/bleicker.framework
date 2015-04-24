<?php

namespace Tests\Bleicker\Framework\Unit\Utility;

use Bleicker\Framework\Utility\ObjectManager;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClass;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClassHavingConstructorArgument;
use Tests\Bleicker\Framework\UnitTestCase;
use StdClass;

/**
 * Class ObjectManagerTest
 *
 * @package Tests\Bleicker\Framework\Unit\Utility
 */
class ObjectManagerTest extends UnitTestCase {

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::prune();
	}

	/**
	 * @test
	 */
	public function pruneTest() {
		ObjectManager::makeSingleton(SimpleClass::class);
		$this->assertTrue(ObjectManager::isSingleton(SimpleClass::class));
		ObjectManager::prune();
		$this->assertFalse(ObjectManager::isSingleton(SimpleClass::class));
	}

	/**
	 * @test
	 */
	public function getClassWithoutAnyContructorArgumentReturnsInstance() {
		$object = ObjectManager::get(SimpleClass::class);
		$this->assertInstanceOf(SimpleClass::class, $object);
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Framework\Exception\ArgumentsGivenButImplementationIsAlreadyAnObjectException
	 */
	public function getClassOrInterfaceThrowsExceptionIfImplementationIsAlreadyAnObjectAndArgumentsGiven() {
		ObjectManager::register(SimpleClassHavingConstructorArgument::class, new SimpleClassHavingConstructorArgument('foo'));
		ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
	}

	/**
	 * @test
	 */
	public function getClassWithoutOneContructorArgumentReturnsInstance() {
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertEquals('foo', $object->getTitle());
	}

	/**
	 * @test
	 */
	public function getClassFromRegistriesImplementationReturnsRegistryInstance() {
		ObjectManager::register(SimpleClass::class, new SimpleClass());
		$object = ObjectManager::get(SimpleClass::class);
		$this->assertTrue(ObjectManager::getImplementation(SimpleClass::class) === $object);
	}

	/**
	 * @test
	 */
	public function getClassFromRegistriesImplementationReturnsRegistryInstanceIfImplemantationIsAClosure() {
		ObjectManager::register(SimpleClassHavingConstructorArgument::class, function ($title) {
			return new SimpleClassHavingConstructorArgument($title);
		});
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertEquals('foo', $object->getTitle());
	}

	/**
	 * @test
	 */
	public function getSingletonClosureIsRegistedAsConcreteImplementation() {
		ObjectManager::register(SimpleClassHavingConstructorArgument::class, function ($title) {
			return new SimpleClassHavingConstructorArgument($title);
		});
		ObjectManager::makeSingleton(SimpleClassHavingConstructorArgument::class);
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, ObjectManager::getImplementation(SimpleClassHavingConstructorArgument::class));
		$this->assertTrue($object === ObjectManager::getImplementation(SimpleClassHavingConstructorArgument::class));
	}
}
