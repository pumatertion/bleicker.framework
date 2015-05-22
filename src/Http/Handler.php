<?php

namespace Bleicker\Framework\Http;

use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\FastRouter\Router;
use Bleicker\Framework\Controller\ControllerInterface;
use Bleicker\Framework\Exception\RedirectException;
use Bleicker\Framework\Http\Exception\IsNotInitializedException;
use Bleicker\Framework\Http\Exception\MethodNotSupportedException;
use Bleicker\Framework\Http\Exception\NoLocaleDefinedException;
use Bleicker\Framework\Http\Exception\NotFoundException;
use Bleicker\Framework\Http\Exception\RequestedLocaleNotDefinedException;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\HttpApplicationResponseInterface;
use Bleicker\Framework\RequestHandlerInterface;
use Bleicker\Framework\Security\Vote\Exception\ControllerInvocationExceptionInterface;
use Bleicker\Framework\Utility\Arrays;
use Bleicker\Framework\Validation\Exception\ValidationException;
use Bleicker\Framework\Validation\Exception\ValidationExceptionInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Routing\ResultInterface;
use Bleicker\Routing\RouterInterface;
use Bleicker\Security\Exception\AbstractVoteException;
use Bleicker\Security\SecurityManagerInterface;
use Bleicker\Translation\LocaleInterface;
use Bleicker\Translation\LocalesInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class Handler
 *
 * @package Bleicker\Framework\Http
 */
class Handler implements RequestHandlerInterface {

	const SYSTEM_LOCALE_NAME = 'systemLocale';

	/**
	 * @var HttpApplicationRequestInterface
	 */
	protected $httpApplicationRequest;

	/**
	 * @var HttpApplicationResponseInterface
	 */
	protected $httpApplicationResponse;

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
	 * @var ControllerInvocationExceptionInterface
	 */
	protected $controllerInvocationException;

	/**
	 * @var ValidationExceptionInterface
	 */
	protected $controllerValidationException;

	/**
	 * @var LocalesInterface $locales
	 */
	protected $locales;

	/**
	 * @var SecurityManagerInterface
	 */
	protected $securityManager;

	/**
	 * @var AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @var boolean
	 */
	protected $isInitialized = FALSE;

	/**
	 * @return boolean
	 */
	public function isInitialized() {
		return $this->isInitialized;
	}

	/**
	 * @return $this
	 */
	public function initialize() {
		$this->isInitialized = TRUE;

		$this->httpApplicationRequest = ObjectManager::get(HttpApplicationRequestInterface::class);
		$this->httpApplicationResponse = ObjectManager::get(HttpApplicationResponseInterface::class);
		$this->locales = ObjectManager::get(LocalesInterface::class);
		$this->router = ObjectManager::get(RouterInterface::class);
		$this->securityManager = ObjectManager::get(SecurityManagerInterface::class);
		$this->authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class);

		$routingResult = $this->invokeRouter();
		$this->controllerName = $routingResult->getRoute()->getClassName();
		$this->methodName = $routingResult->getRoute()->getMethodName();
		$this->methodArguments = $this->extractMethodArguments($routingResult);
		$systemLocale = $this->extractSystemLocale($routingResult);
		$this->locales->setSystemLocale($systemLocale);
		setlocale(LC_ALL, (string)$systemLocale);
		return $this;
	}

	/**
	 * @return HttpApplicationRequestInterface
	 */
	public function getApplicationRequest() {
		return $this->httpApplicationRequest;
	}

	/**
	 * @return HttpApplicationResponseInterface
	 */
	public function getApplicationResponse() {
		return $this->httpApplicationResponse;
	}

	/**
	 * @return $this
	 */
	public function run() {
		if (!$this->isInitialized()) {
			$this->initialize();
		}

		return $this->callControllerMethod();
	}

	/**
	 * @return $this
	 * @throws IsNotInitializedException
	 * @throws AbstractVoteException
	 */
	protected function callControllerMethod() {

		$this->authenticationManager->run();

		if ($this->securityManager->vote($this->controllerName . '::' . $this->methodName)->getResults()->count()) {
			$firstVoteException = $this->securityManager->getResults()->first();
			if ($firstVoteException instanceof ControllerInvocationExceptionInterface) {
				$this->controllerInvocationException = $firstVoteException;
				$this->controllerName = $firstVoteException->getControllerName();
				$this->methodName = $firstVoteException->getMethodName();
			} else {
				/** @var AbstractVoteException $firstVoteException */
				throw $firstVoteException;
			}
		}

		/** @var ControllerInterface $controller */
		$controller = new $this->controllerName();
		$controller
			->setInvokingException($this->controllerInvocationException)
			->setValidationException($this->controllerValidationException)
			->setRequest($this->httpApplicationRequest)
			->setResponse($this->httpApplicationResponse)
			->resolveFormat($this->methodName)
			->resolveView($this->methodName);
		try {
			$content = call_user_func_array(array($controller, $this->methodName), $this->methodArguments);
			if (!empty($content)) {
				$this->httpApplicationResponse->getParentResponse()->setContent($content);
			}
		} catch (RedirectException $redirect) {
			/** @var Response $httpResponse */
			$httpResponse = $this->httpApplicationResponse->getParentResponse();
			$httpResponse->headers->set('Location', $redirect->getUri());
			$httpResponse->setStatusCode($redirect->getStatus(), $redirect->getMessage());
			$httpResponse->send();
		} catch (ValidationException $exception) {
			$this->controllerValidationException = $exception;
			$this->controllerName = $exception->getControllerName();
			$this->methodName = $exception->getMethodName();
			$this->methodArguments = $exception->getMethodArguments();
			$this->callControllerMethod();
		}

		return $this;
	}

	/**
	 * @param ResultInterface $result
	 * @return LocaleInterface
	 * @throws NoLocaleDefinedException
	 * @throws RequestedLocaleNotDefinedException
	 */
	protected function extractSystemLocale(ResultInterface $result) {
		$arguments = $result->getArguments();
		$systemLocale = Arrays::getValueByPath($arguments, static::SYSTEM_LOCALE_NAME);
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
	 * @param ResultInterface $result
	 * @return array
	 */
	protected function extractMethodArguments(ResultInterface $result) {
		$arguments = $result->getArguments();
		$methodArguments = [];
		$methodReflection = new \ReflectionMethod($result->getRoute()->getClassName(), $result->getRoute()->getMethodName());
		$availableParameters = $methodReflection->getParameters();
		/** @var ReflectionParameter $parameter */
		foreach ($availableParameters as $parameter) {
			$methodArguments[$parameter->getName()] = Arrays::getValueByPath($arguments, $parameter->getName());
		}
		return $methodArguments;
	}

	/**
	 * @return ResultInterface
	 * @throws Exception\MethodNotSupportedException
	 * @throws Exception\NotFoundException
	 */
	protected function invokeRouter() {
		$uri = $this->httpApplicationRequest->getParentRequest()->getPathInfo();
		$method = $this->httpApplicationRequest->getParentRequest()->getMethod();
		$result = $this->router->dispatch($uri, $method);
		switch ($result->getStatus()) {
			case Router::NOT_FOUND:
				throw new NotFoundException('The uri "' . $uri . '" does not exist.', 1429187150);
			case Router::METHOD_NOT_ALLOWED:
				throw new MethodNotSupportedException('The "' . $uri . '" does not support the requested method "' . $method . '"', 1429187151);
			default:
				return $result;
		}
	}
}
