<?php

namespace Bleicker\Framework\Http;
use Bleicker\Framework\Utility\Arrays;

/**
 * Class ResponseFactory
 *
 * @package Bleicker\Framework\Http
 */
class ResponseFactory {

	/**
	 * @param Request $request
	 * @return Response|JsonResponse
	 */
	public static function getInstance(Request $request) {
		$acceptableContentTypes = $request->getAcceptableContentTypes();
		if (Arrays::getValueByPath($acceptableContentTypes, '0') === 'application/json') {
			return static::getJsonResponse();
		}
		return static::getResponse();
	}

	/**
	 * @return JsonResponse
	 */
	public static function getJsonResponse() {
		return JsonResponse::create();
	}

	/**
	 * @return Response
	 */
	public static function getResponse() {
		return Response::create();
	}
}
