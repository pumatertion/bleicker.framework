<?php

namespace Tests\Bleicker\Framework\Unit\Converter;

use Bleicker\Converter\Converter;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\Framework\Http\Request;
use Bleicker\Registry\Utility\Arrays;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ApplicationRequestConverter
 *
 * @package Tests\Bleicker\Framework\Unit\Converter
 */
class ApplicationRequestConverterTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		Converter::prune();
		WellformedApplicationRequestConverter::register();
		JsonApplicationRequestConverter::register();
	}

	protected function tearDown() {
		parent::tearDown();
		Converter::prune();
	}

	/**
	 * @test
	 */
	public function wellformedTest() {
		$server = [];
		Arrays::setValueByPath($server, 'CONTENT_TYPE', 'application/x-www-form-urlencoded');

		$httpRequest = Request::create('http://example.com/foo', 'get', [], [], [], $server, NULL);

		/** @var ApplicationRequestInterface $applicationRequest */
		$applicationRequest = Converter::convert($httpRequest, ApplicationRequestInterface::class);

		$this->assertInstanceOf(ApplicationRequestInterface::class, $applicationRequest);
	}

	/**
	 * @test
	 */
	public function wellformedGetTest() {
		$server = [];
		Arrays::setValueByPath($server, 'CONTENT_TYPE', 'application/x-www-form-urlencoded');

		$httpRequest = Request::create('http://example.com/foo?foo[bar]=baz', 'get', [], [], [], $server, NULL);

		/** @var ApplicationRequestInterface $applicationRequest */
		$applicationRequest = Converter::convert($httpRequest, ApplicationRequestInterface::class);

		$this->assertTrue(is_array($applicationRequest->getParameters()));
		$this->assertEquals('baz', $applicationRequest->getParameter('foo.bar'));
	}

	/**
	 * @test
	 */
	public function wellformedPostTest() {
		$server = [];
		$content = 'foo[bar]=baz';

		Arrays::setValueByPath($server, 'CONTENT_TYPE', 'application/x-www-form-urlencoded');

		$httpRequest = Request::create('http://example.com/foo', 'post', [], [], [], $server, $content);

		/** @var ApplicationRequestInterface $applicationRequest */
		$applicationRequest = Converter::convert($httpRequest, ApplicationRequestInterface::class);

		$this->assertTrue(is_array($applicationRequest->getContents()));
		$this->assertEquals('baz', $applicationRequest->getContent('foo.bar'));
	}

	/**
	 * @test
	 */
	public function wellformedGetPostTest() {
		$server = [];
		$content = 'foo[bar]=baz';

		Arrays::setValueByPath($server, 'CONTENT_TYPE', 'application/x-www-form-urlencoded');

		$httpRequest = Request::create('http://example.com/foo?foo[bar]=baz', 'post', [], [], [], $server, $content);

		/** @var ApplicationRequestInterface $applicationRequest */
		$applicationRequest = Converter::convert($httpRequest, ApplicationRequestInterface::class);

		$this->assertTrue(is_array($applicationRequest->getParameters()));
		$this->assertTrue(is_array($applicationRequest->getContents()));
		$this->assertEquals('baz', $applicationRequest->getParameter('foo.bar'));
		$this->assertEquals('baz', $applicationRequest->getContent('foo.bar'));
	}

	/**
	 * @test
	 */
	public function jsonPostTest() {
		$server = [];
		$content = '{"foo":{"bar":"baz"}}';

		Arrays::setValueByPath($server, 'CONTENT_TYPE', 'application/json');

		$httpRequest = Request::create('http://example.com/foo?foo[bar]=baz', 'post', [], [], [], $server, $content);

		/** @var ApplicationRequestInterface $applicationRequest */
		$applicationRequest = Converter::convert($httpRequest, ApplicationRequestInterface::class);

		$this->assertTrue(is_array($applicationRequest->getContents()));
		$this->assertEquals('baz', $applicationRequest->getContent('foo.bar'));
	}
}
