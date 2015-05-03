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
	public function hasEmptyArgumentsOnPostByDefaultTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		$request = RequestFactory::getInstance();
		$arguments = $request->getArguments()->all();
		$this->assertEmpty($arguments);
	}
}
