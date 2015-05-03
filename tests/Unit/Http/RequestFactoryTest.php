<?php

namespace Tests\Bleicker\Framework\Unit\Http;

use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\RequestFactory;
use Bleicker\Registry\Utility\Arrays;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class FactoryTest
 *
 * @package Tests\Bleicker\Framework\Unit\Http
 */
class RequestFactoryTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function getInstanceTest() {
		$request = RequestFactory::getInstance();
		$this->assertInstanceOf(Request::class, $request);
	}

	/**
	 * @test
	 */
	public function hasArgumentsTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_URI', '/foo/bar/baz?foo=bar');
		$request = RequestFactory::getInstance();
		$parameter = $request->getParameter()->all();
		$this->assertEquals('bar', Arrays::getValueByPath($parameter, 'foo'));
	}

	/**
	 * @test
	 */
	public function hasContentOnPostTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		$request = RequestFactory::getInstance();
		$this->assertNotNull($request->getContent());
	}

	/**
	 * @test
	 */
	public function hasContentAndParameterOnPostTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_URI', '/foo/bar/baz?foo=bar');
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		$request = RequestFactory::getInstance();
		$parameter = $request->getParameter()->all();
		$this->assertNotNull($request->getContent());
		$this->assertEquals('bar', Arrays::getValueByPath($parameter, 'foo'));
	}

	/**
	 * @test
	 */
	public function hasContentOnPatchTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'PATCH');
		$request = RequestFactory::getInstance();
		$this->assertNotNull($request->getContent());
	}

	/**
	 * @test
	 */
	public function hasContentOnPutTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'PUT');
		$request = RequestFactory::getInstance();
		$this->assertNotNull($request->getContent());
	}

	/**
	 * @test
	 */
	public function hasNoContentForGetTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'GET');
		$request = RequestFactory::getInstance();
		$this->assertNull($request->getContent());
	}

	/**
	 * @test
	 */
	public function hasNoContentForOptionTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'OPTION');
		$request = RequestFactory::getInstance();
		$this->assertNull($request->getContent());
	}
}
