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
		Arrays::setValueByPath($_SERVER, 'PATH_INFO', '/foo/bar/baz');
		Arrays::setValueByPath($_GET, 'foo', 'bar');
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

	/**
	 * @test
	 */
	public function hasContentJsonRequestArgumentsOnPostByDefaultTest() {
		Arrays::setValueByPath($_SERVER, 'REQUEST_METHOD', 'POST');
		Arrays::setValueByPath($_SERVER, 'CONTENT_TYPE', 'application/json');
		$request = RequestFactory::getInstance();
		$content = $request->getContent();
		$this->assertEmpty($content);
	}
}
