<?php

namespace Bleicker\Framework\Http;

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
		if ($request->getHeaders()->get('CONTENT-TYPE') === 'application/json') {
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
