<?php

namespace Bleicker\Framework\Http;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Registry\Utility\Arrays;

/**
 * Class RequestFactory
 *
 * @package Bleicker\Framework\Http
 */
class RequestFactory {

	/**
	 * @var array
	 */
	public static $methodTypesSupportingContent = ['put', 'patch', 'post'];

	/**
	 * @return Request
	 */
	public static function getInstance() {
		$request = Request::createFromGlobals();
		/** @var SessionInterface $session */
		$session = ObjectManager::get(SessionInterface::class, function () {
			$session = new Session();
			ObjectManager::add(SessionInterface::class, $session, TRUE);
			return $session;
		});
		$request->setSession($session);
		return $request;
	}

	/**
	 * @return string
	 */
	protected static function getContent() {
		if (!static::supportsContent()) {
			return NULL;
		}
		return file_get_contents('php://input');
	}

	/**
	 * @return boolean
	 */
	protected static function supportsContent() {
		return in_array(static::getMethod(), static::$methodTypesSupportingContent);
	}

	/**
	 * @return array
	 * @see https://bugs.php.net/bug.php?id=66606 With the php's bug #66606, the php's built-in web server stores the Content-Type and Content-Length header values in HTTP_CONTENT_TYPE and HTTP_CONTENT_LENGTH fields.
	 */
	protected static function getServer() {
		$server = $_SERVER;
		if ('cli-server' === php_sapi_name()) {
			if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
				$server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
			}
			if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
				$server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
			}
		}
		return $server;
	}

	/**
	 * @return string
	 */
	protected static function getMethod() {
		return strtolower(Arrays::getValueByPath($_SERVER, 'REQUEST_METHOD'));
	}

	/**
	 * @return string
	 */
	protected static function getUri() {
		$requestUri = Arrays::getValueByPath($_SERVER, 'REQUEST_URI');
		$uri = $requestUri;
		return $uri;
	}
}
