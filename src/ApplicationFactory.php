<?php

namespace Bleicker\Framework;

use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Context\ContextInterface;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\Framework\Http\Handler;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\RequestFactory;
use Bleicker\Framework\Http\Response;
use Bleicker\Framework\Http\ResponseFactory;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\SecurityManager;
use Bleicker\Security\SecurityManagerInterface;
use Bleicker\Translation\Locales;
use Bleicker\Translation\LocalesInterface;
use Exception;

/**
 * Class ApplicationFactory
 *
 * @package Bleicker\Framework
 */
class ApplicationFactory {

	/**
	 * @return HttpApplicationInterface
	 */
	public static function http() {
		/**
		 * Get implementation of ContextInterface and if not exists use fallback function and register it as singleton
		 *
		 * @var RequestHandlerInterface $requestHandler
		 */
		$requestHandler = ObjectManager::get(RequestHandlerInterface::class, function () {

			/**
			 * Get implementation of ContextInterface and if not exists use fallback function and register it as singleton
			 *
			 * @var ContextInterface $context
			 */
			$context = ObjectManager::get(ContextInterface::class, function () {
				$context = new Context();
				ObjectManager::add(ContextInterface::class, $context, TRUE);
				return $context;
			});

			/**
			 * Get implementation of HttpApplicationRequestInterface as singleton
			 *
			 * @var HttpApplicationRequestInterface $applicationRequest
			 */
			$applicationRequest = ObjectManager::get(HttpApplicationRequestInterface::class, function () {
				/**
				 * Get implementation of Http/Request and if not exists use fallback function and register it as singleton
				 *
				 * @var Request $httpRequest
				 */
				$httpRequest = ObjectManager::get(Request::class, function () {
					$request = RequestFactory::getInstance();
					ObjectManager::add(Request::class, $request, TRUE);
					return $request;
				});

				/**
				 * Get implementation of ConverterInterface and if not exists use fallback function and register it as singleton
				 *
				 * @var ConverterInterface $converter
				 */
				$converter = ObjectManager::get(ConverterInterface::class, function () {
					$converter = new Converter();
					ObjectManager::add(ConverterInterface::class, $converter, TRUE);
					WellformedApplicationRequestConverter::register();
					JsonApplicationRequestConverter::register();
					return $converter;
				});

				/**
				 * Using converter to get ApplicationRequest by Request and register it as singleton implementation
				 *
				 * @var HttpApplicationRequestInterface $applicationRequest
				 */
				$applicationRequest = $converter->convert($httpRequest, HttpApplicationRequestInterface::class);
				ObjectManager::add(HttpApplicationRequestInterface::class, $applicationRequest, TRUE);

				return $applicationRequest;
			});

			/**
			 * Get implementation of HttpApplicationResponseInterface and if not exists use fallback function and register it as singleton
			 *
			 * @var HttpApplicationResponseInterface $applicationResponse
			 * @todo Converter from httprequest to httpapplicationresponse
			 * @todo ResponseFactory
			 */
			$applicationResponse = ObjectManager::get(HttpApplicationResponseInterface::class, function () {
				/**
				 * Get implementation of Response and if not exists use fallback function and register it as singleton
				 *
				 * @var Response $httpResponse
				 */
				$httpResponse = ObjectManager::get(Response::class, function () {
					/**
					 * Get implementation of Http/Request and if not exists use fallback function and register it as singleton
					 *
					 * @var Request $httpRequest
					 */
					$httpRequest = ObjectManager::get(Request::class, function () {
						$request = RequestFactory::getInstance();
						ObjectManager::add(Request::class, $request, TRUE);
						return $request;
					});

					$response = ResponseFactory::getInstance($httpRequest);
					$response->prepare($httpRequest);

					ObjectManager::add(Response::class, $response, TRUE);
					return $response;
				});

				$applicationResponse = new HttpApplicationResponse($httpResponse);
				ObjectManager::add(HttpApplicationResponseInterface::class, $applicationResponse, TRUE);
				return $applicationResponse;
			});

			/**
			 * Get implementation of RouterInterface and if not exists use fallback function and register it as singleton
			 *
			 * @var RouterInterface $router
			 */
			$router = ObjectManager::get(RouterInterface::class, function () {
				/**
				 * Get implementation of ContextInterface and if not exists use fallback function and register it as singleton
				 *
				 * @var ContextInterface $context
				 */
				$context = ObjectManager::get(ContextInterface::class, function () {
					$context = new Context();
					ObjectManager::add(ContextInterface::class, $context, TRUE);
					return $context;
				});

				$router = Router::getInstance(__DIR__ . '/../route.cache.php', !$context->isProduction());
				ObjectManager::add(RouterInterface::class, $router, TRUE);
				return $router;
			});

			/**
			 * Get implementation of LocalesInterface and if not exists use fallback function and register it as singleton
			 *
			 * @var LocalesInterface $locales
			 */
			$locales = ObjectManager::get(LocalesInterface::class, function () {
				$locales = new Locales();
				ObjectManager::add(LocalesInterface::class, $locales, TRUE);
				return $locales;
			});

			/**
			 * Get implementation of SecurityManagerInterface and if not exists use fallback function and register it as singleton
			 *
			 * @var SecurityManagerInterface $securityManager
			 */
			$securityManager = ObjectManager::get(SecurityManagerInterface::class, function () {
				$securityManager = new SecurityManager();
				ObjectManager::add(SecurityManagerInterface::class, $securityManager, TRUE);
				return $securityManager;
			});

			$requestHandler = new Handler($context, $applicationRequest, $applicationResponse, $locales, $router, $securityManager);
			ObjectManager::add(RequestHandlerInterface::class, $requestHandler, TRUE);
			return $requestHandler;
		});

		$application = new HttpApplication($requestHandler);

		/** Register HttpApplication implementation as singleton */
		ObjectManager::add(HttpApplicationInterface::class, $application, TRUE);

		return $application;
	}

	/**
	 * @return ApplicationInterface
	 * @throws Exception
	 */
	public static function cli() {
		throw new Exception('Currently not supported');
	}
}
