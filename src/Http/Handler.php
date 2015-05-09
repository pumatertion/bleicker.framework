<?php

namespace Bleicker\Framework\Http;

use Bleicker\Converter\Converter;
use Bleicker\Converter\ConverterInterface;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Context\Context;
use Bleicker\Framework\Context\ContextInterface;
use Bleicker\Framework\Controller\ControllerInterface;
use Bleicker\Framework\Exception\RedirectException;
use Bleicker\Framework\Http\Exception\ControllerRouteDataInterfaceRequiredException;
use Bleicker\Framework\Http\Exception\MethodNotSupportedException;
use Bleicker\Framework\Http\Exception\NoLocaleDefinedException;
use Bleicker\Framework\Http\Exception\NotFoundException;
use Bleicker\Framework\Http\Exception\RequestedLocaleNotDefinedException;
use Bleicker\Framework\Security\AccessVoter;
use Bleicker\Framework\Security\AccessVoterInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Request\HandlerInterface;
use Bleicker\Request\MainRequestInterface;
use Bleicker\Response\ApplicationResponse;
use Bleicker\Response\Http\Response;
use Bleicker\Response\MainResponseInterface;
use Bleicker\Response\ResponseInterface as ApplicationResponseInterface;
use Bleicker\Routing\ControllerRouteDataInterface;
use Bleicker\Routing\RouteInterface;
use Bleicker\Routing\RouterInterface;
use Bleicker\Translation\LocaleInterface;
use Bleicker\Translation\Locales;
use Bleicker\Translation\LocalesInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use ReflectionMethod;
use ReflectionParameter;
use Bleicker\Session\Session;
use Bleicker\Session\SessionInterface;

/**
 * Class Handler
 *
 * @package Bleicker\Framework\Http
 */
class Handler implements HandlerInterface {

	const SYSTEM_LOCALE_NAME = 'systemLocale';

	/**
	 * @var ApplicationRequestInterface
	 */
	protected $request;

	/**
	 * @var ApplicationResponseInterface
	 */
	protected $response;

	/**
	 * @var RouterInterface
	 */
	protected $router;

	/**
	 * @var string
	 */
	protected $controllerName;

	/**
	 * @var string
	 */
	protected $methodName;

	/**
	 * @var array
	 */
	protected $methodArguments;

	/**
	 * @var LocalesInterface $locales
	 */
	protected $locales;

	/**
	 * @var ContextInterface $context
	 */
	protected $context;

	/**
	 * @return $this
	 */
	/**
	 * @return $this
	 */
	public function initialize() {

		/** @var RequestInterface $httpRequest */
		$httpRequest = ObjectManager::get(MainRequestInterface::class, function () {
			$request = RequestFactory::getInstance();
			ObjectManager::add(MainRequestInterface::class, $request, TRUE);
			return $request;
		});

		/** @var SessionInterface $session */
		$session = ObjectManager::get(SessionInterface::class, function(){
			$session = new Session();
			ObjectManager::add(SessionInterface::class, $session, TRUE);
			return $session;
		});

		$httpRequest->setSession($session);

		/** @var MainResponseInterface $httpResponse */
		$httpResponse = ObjectManager::get(MainResponseInterface::class, function(){
			$response = new Response();
			ObjectManager::add(MainResponseInterface::class, $response, TRUE);
			return $response;
		});

		/** @var ConverterInterface $converter */
		$converter = ObjectManager::get(ConverterInterface::class, function(){
			$converter = new Converter();
			ObjectManager::add(ConverterInterface::class, $converter, TRUE);
			return $converter;
		});

		$this->request = ObjectManager::get(ApplicationRequestInterface::class, function() use($converter, $httpRequest){
			$applicationRequest = $converter->convert($httpRequest, ApplicationRequestInterface::class);
			ObjectManager::add(ApplicationRequestInterface::class, $applicationRequest, TRUE);
		});

		$this->response = new ApplicationResponse($httpResponse);
		$this->response = ObjectManager::get(ApplicationResponseInterface::class, function() use ($httpResponse){
			$applicationResponse = new ApplicationResponse($httpResponse);
			ObjectManager::add(ApplicationResponseInterface::class, $applicationResponse, TRUE);
		});

		$this->router = ObjectManager::get(RouterInterface::class, function () {
			$router = Router::getInstance(__DIR__ . '/../route.cache.php', $this->context->isProduction() ? FALSE : TRUE);
			ObjectManager::add(RouterInterface::class, $router, TRUE);
			return $router;
		});

		$this->locales = ObjectManager::get(LocalesInterface::class, function(){
			$locales = new Locales();
			ObjectManager::add(LocalesInterface::class, $locales, TRUE);
			return $locales;
		});

		$this->context = ObjectManager::get(ContextInterface::class, function(){
			$context = new Context();
			ObjectManager::add(ContextInterface::class, $context, TRUE);
			return $context;
		});

		$routerInformation = $this->invokeRouter();
		$this->controllerName = $this->getControllerNameByRoute($routerInformation[1]);
		$this->methodName = $this->getMethodNameByRoute($routerInformation[1]);
		$this->methodArguments = $this->getMethodArgumentsByRouterInformation($this->controllerName, $this->methodName, $routerInformation[2]);
		$systemLocale = $this->getSystemLocaleByRoute($routerInformation[2]);
		$this->locales->setSystemLocale($systemLocale);
		setlocale(LC_ALL, (string)$systemLocale);
		return $this;
	}

	/**
	 * @param array $routeData
	 * @return LocaleInterface
	 * @throws NoLocaleDefinedException
	 * @throws RequestedLocaleNotDefinedException
	 */
	protected function getSystemLocaleByRoute(array $routeData = array()) {
		$systemLocale = Arrays::getValueByPath($routeData, static::SYSTEM_LOCALE_NAME);
		$availableLocales = new ArrayCollection($this->locales->storage());

		if ($availableLocales->count() === 0) {
			throw new NoLocaleDefinedException('No locales defined. Please Register at least one Locale with \Bleicker\Translation\Locale::register(...)', 1431028712);
		}

		if ($systemLocale !== NULL) {
			$partitions = explode(LocaleInterface::LOCALE_SEPARATOR, $systemLocale);

			$language = Arrays::getValueByPath($partitions, '0');
			$region = Arrays::getValueByPath($partitions, '1');
			$expr = Criteria::expr();
			$criteria = Criteria::create();

			if ($region === NULL) {
				$criteria->andWhere(
					$expr->andX(
						$expr->eq('language', $language)
					)
				);
			} else {
				$criteria->andWhere(
					$expr->andX(
						$expr->eq('language', strtolower($language)),
						$expr->eq('region', strtoupper($region))
					)
				);
			}

			$matchingTranslations = $availableLocales->matching($criteria);

			if ($matchingTranslations->count() === 0) {
				throw new RequestedLocaleNotDefinedException('Requested locale "' . $systemLocale . '" is not defined', 1431030043);
			}

			return $matchingTranslations->first();
		}

		return $this->locales->getDefault();
	}

	/**
	 * @param RouteInterface $route
	 * @return string
	 * @throws ControllerRouteDataInterfaceRequiredException
	 */
	protected function getControllerNameByRoute(RouteInterface $route) {
		/** @var ControllerRouteDataInterface $controllerRouteData */
		$controllerRouteData = $route->getData();

		if (!($controllerRouteData instanceof ControllerRouteDataInterface)) {
			throw new ControllerRouteDataInterfaceRequiredException('No instance of ControllerRouteDataInterface given', 1429338660);
		}

		return $controllerRouteData->getController();
	}

	/**
	 * @param RouteInterface $route
	 * @return string
	 * @throws ControllerRouteDataInterfaceRequiredException
	 */
	protected function getMethodNameByRoute(RouteInterface $route) {
		/** @var ControllerRouteDataInterface $controllerRouteData */
		$controllerRouteData = $route->getData();

		if (!($controllerRouteData instanceof ControllerRouteDataInterface)) {
			throw new ControllerRouteDataInterfaceRequiredException('No instance of ControllerRouteDataInterface given', 1429338661);
		}

		return $controllerRouteData->getMethod();
	}

	/**
	 * @param string $controllerName
	 * @param string $methodName
	 * @param array $arguments
	 * @return array
	 */
	protected function getMethodArgumentsByRouterInformation($controllerName, $methodName, array $arguments = array()) {
		$methodArguments = [];
		$methodReflection = new \ReflectionMethod($controllerName, $methodName);
		$availableParameters = $methodReflection->getParameters();
		/** @var ReflectionParameter $parameter */
		foreach ($availableParameters as $parameter) {
			$methodArguments[$parameter->getName()] = Arrays::getValueByPath($arguments, $parameter->getName());
		}
		return $methodArguments;
	}

	/**
	 * @todo mapping to objects here?
	 * @return $this
	 * @throws ControllerRouteDataInterfaceRequiredException
	 */
	public function handle() {
		/** @var AccessVoterInterface $accessVoter */
		$accessVoter = ObjectManager::get(AccessVoterInterface::class, function(){
			$accessVoter = new AccessVoter();
			ObjectManager::add(AccessVoterInterface::class, $accessVoter, TRUE);
			return $accessVoter;
		});

		return $accessVoter->vote($this->controllerName . '::' . $this->methodName, function () {
			/** @var ControllerInterface $controller */
			$controller = new $this->controllerName();
			$controller
				->setRequest($this->request)
				->setResponse($this->response)
				->resolveFormat($this->methodName)
				->resolveView($this->methodName);
			try {
				$content = call_user_func_array(array($controller, $this->methodName), $this->methodArguments);
				if (!empty($content)) {
					/** @var Response $httpResponse */
					$httpResponse = $this->response->getMainResponse();
					$httpResponse->setContent($content);
				}
			} catch (RedirectException $redirect) {
				/** @var Response $httpResponse */
				$httpResponse = $this->response->getMainResponse();
				$httpResponse->headers->set('Location', $redirect->getUri());
				$httpResponse->setStatusCode($redirect->getStatus(), $redirect->getMessage());
				$httpResponse->send();
			}

			return $this;
		}, $this->methodArguments);
	}

	/**
	 * @return array
	 * @throws Exception\NotFoundException
	 * @throws Exception\MethodNotSupportedException
	 */
	protected function invokeRouter() {
		$routeResult = $this->router->dispatch($this->request->getMainRequest()->getPathInfo(), $this->request->getMainRequest()->getMethod());
		switch ($routeResult[0]) {
			case RouterInterface::NOT_FOUND:
				throw new NotFoundException('Not Found', 1429187150);
			case RouterInterface::METHOD_NOT_ALLOWED:
				throw new MethodNotSupportedException('Method not allowed', 1429187151);
			case RouterInterface::FOUND:
				return $routeResult;
		}
	}
}
