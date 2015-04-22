<?php

namespace Tests\Bleicker\Framework\Unit\Utility;

use Bleicker\Framework\Registry;
use Bleicker\Framework\Utility\ObjectManager;
use Tests\Bleicker\Framework\BaseTestCase;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClass;
use Tests\Bleicker\Framework\Unit\Fixtures\SimpleClassHavingConstructorArgument;

/**
 * Class ObjectManagerTest
 *
 * @package Tests\Bleicker\Framework\Unit\Utility
 */
class ObjectManagerTest extends BaseTestCase {

	protected function tearDown() {
		parent::tearDown();
		Registry::prune();
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
		Registry::addImplementation(SimpleClassHavingConstructorArgument::class, new SimpleClassHavingConstructorArgument('foo'));
		ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
	}

	/**
	 * @test
	 */
	public function getClassWithoutOneContructorArgumentReturnsInstance() {
		Registry::prune();
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertEquals('foo', $object->getTitle());
	}

	/**
	 * @test
	 */
	public function getClassFromRegistriesImplementationReturnsRegistryInstance() {
		Registry::addImplementation(SimpleClass::class, new SimpleClass());
		$object = ObjectManager::get(SimpleClass::class);
		$this->assertTrue(Registry::getImplementation(SimpleClass::class) === $object);
	}

	/**
	 * @test
	 */
	public function getClassFromRegistriesImplementationReturnsRegistryInstanceIfImplemantationIsAClosure() {
		Registry::addImplementation(SimpleClassHavingConstructorArgument::class, function($title){
			return new SimpleClassHavingConstructorArgument($title);
		});
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertEquals('foo', $object->getTitle());
	}

	/**
	 * @test
	 */
	public function getSingletonClosureIsRegistedAsConcreteImplementation(){
		Registry::addImplementation(SimpleClassHavingConstructorArgument::class, function($title){
			return new SimpleClassHavingConstructorArgument($title);
		});
		Registry::makeSingletonImplementation(SimpleClassHavingConstructorArgument::class);
		$object = ObjectManager::get(SimpleClassHavingConstructorArgument::class, 'foo');
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, $object);
		$this->assertInstanceOf(SimpleClassHavingConstructorArgument::class, Registry::getImplementation(SimpleClassHavingConstructorArgument::class));
		$this->assertTrue($object === Registry::getImplementation(SimpleClassHavingConstructorArgument::class));
	}

}
