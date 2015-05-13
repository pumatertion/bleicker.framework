<?php

namespace Bleicker\Framework;

use Bleicker\Authentication\AuthenticationManager;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Context\ContextInterface;
use Bleicker\Framework\Converter\JsonApplicationRequestConverter;
use Bleicker\Framework\Converter\JsonApplicationRequestConverterInterface;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverterInterface;
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
use Closure;
use Exception;

/**
 * Class ApplicationFactory
 *
 * @package Bleicker\Framework
 */
class ApplicationFactory {

	/**
	 * @param Closure $before
	 * @param Closure $after
	 * @return HttpApplicationInterface
	 */
	public static function http(Closure $before = NULL, Closure $after = NULL) {

		/**
		 * Invoke before configuration
		 */
		if ($before !== NULL) {
			$instance = new static;
			call_user_func_array($before, [$instance]);
		}

		/**
		 * Get implementation of ContextInterface and if not exists use fallback function and register it as singleton
		 */
		ObjectManager::get(RequestHandlerInterface::class, function () use ($before, $after) {
			/**
			 * Get implementation of ContextInterface and if not exists use fallback function and register it as singleton
			 */
			ObjectManager::get(ContextInterface::class, function () {
				$context = new Context();
				ObjectManager::add(ContextInterface::class, $context, TRUE);
				return $context;
			});

			/**
			 * Get implementation of RouterInterface and if not exists use fallback function and register it as singleton
			 */
			ObjectManager::get(RouterInterface::class, function () {
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
			 */
			ObjectManager::get(LocalesInterface::class, function () {
				$locales = new Locales();
				ObjectManager::add(LocalesInterface::class, $locales, TRUE);
				return $locales;
			});

			/**
			 * Get implementation of SecurityManagerInterface and if not exists use fallback function and register it as singleton
			 */
			ObjectManager::get(SecurityManagerInterface::class, function () {
				$securityManager = new SecurityManager();
				ObjectManager::add(SecurityManagerInterface::class, $securityManager, TRUE);
				return $securityManager;
			});

			/**
			 * Get implementation of AuthenticationManagerInterface and if not exists use fallback function and register it as singleton
			 */
			ObjectManager::get(AuthenticationManagerInterface::class, function () {
				$authenticationManager = new AuthenticationManager();
				ObjectManager::add(AuthenticationManagerInterface::class, $authenticationManager, TRUE);
				return $authenticationManager;
			});

			/**
			 * Get implementation of HttpApplicationRequestInterface as singleton
			 */
			ObjectManager::get(HttpApplicationRequestInterface::class, function () {
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
					return $converter;
				});

				/**
				 * Register Wellformed Request Converter
				 */
				if (!$converter->has(WellformedApplicationRequestConverterInterface::class)) {
					WellformedApplicationRequestConverter::register();
				}

				/**
				 * Register Json Request Converter
				 */
				if (!$converter->has(JsonApplicationRequestConverterInterface::class)) {
					JsonApplicationRequestConverter::register();
				}

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
			 */
			ObjectManager::get(HttpApplicationResponseInterface::class, function () {
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

			$requestHandler = new Handler();
			ObjectManager::add(RequestHandlerInterface::class, $requestHandler, TRUE);
			return $requestHandler;
		});

		$application = ObjectManager::get(HttpApplicationInterface::class, function () {
			$httpApplication = new HttpApplication();
			ObjectManager::add(HttpApplicationInterface::class, $httpApplication, TRUE);
			return $httpApplication;
		});

		/**
		 * Invoke after configuration
		 */
		if ($after !== NULL) {
			$instance = new static;
			call_user_func_array($after, [$instance]);
		}

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
