<?php

namespace Bleicker\Framework\Controller;

use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\Controller\Exception\AcceptedContentTypeNotSupportedException;
use Bleicker\Framework\Exception\RedirectException;
use Bleicker\Framework\HttpApplicationRequest;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Response\ApplicationResponse;
use Bleicker\Response\ResponseInterface as ApplicationResponseInterface;
use Bleicker\Translation\Locales;
use Bleicker\Translation\LocalesInterface;
use Bleicker\View\Template\View;
use Bleicker\View\ViewInterface;

/**
 * Class AbstractController
 *
 * @package Bleicker\Framework\Controller
 */
abstract class AbstractController implements ControllerInterface {

	/**
	 * @var HttpApplicationRequest
	 */
	protected $request;

	/**
	 * @var ApplicationResponse
	 */
	protected $response;

	/**
	 * @var ViewInterface
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $format;

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	/**
	 * @var LocalesInterface
	 */
	protected $locales;

	public function __construct() {
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
		$this->locales = ObjectManager::get(LocalesInterface::class, Locales::class);
	}

	/**
	 * @param string $method
	 * @return $this
	 */
	public function resolveView($method) {
		$this->view = new View(static::class, $method, $this->format);
		return $this;
	}

	/**
	 * @param string $method
	 * @return $this
	 * @throws AcceptedContentTypeNotSupportedException
	 */
	public function resolveFormat($method) {
		switch ($this->request->getMainRequest()->getAcceptableContentTypes()[0]) {
			case 'application/json':
				$this->format = 'json';
				break;
			case 'text/html':
			case '*/*':
				$this->format = 'html';
				break;
			default:
				throw new AcceptedContentTypeNotSupportedException('We can not answer in the requested accept type. This controller supports only "application/json" or "text/html"', 1429528425);
		}
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasView() {
		return $this->view !== NULL;
	}

	/**
	 * @return ViewInterface
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * @param ViewInterface $view
	 * @return $this
	 */
	public function setView(ViewInterface $view = NULL) {
		$this->view = $view;
		return $this;
	}

	/**
	 * @param HttpApplicationRequestInterface $request
	 * @return $this
	 */
	public function setRequest(HttpApplicationRequestInterface $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * @return HttpApplicationRequestInterface
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param ApplicationResponseInterface $response
	 * @return $this
	 */
	public function setResponse(ApplicationResponseInterface $response) {
		$this->response = $response;
		return $this;
	}

	/**
	 * @return ApplicationResponseInterface
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param string $uri
	 * @param integer $statusCode
	 * @param string $statusMessage
	 * @param boolean $prefixSystemLocale
	 * @throws RedirectException
	 * @see http://de.wikipedia.org/wiki/HTTP-Statuscode#3xx_.E2.80.93_Umleitung If you want to use the original request method use 307 status code.
	 */
	public function redirect($uri, $statusCode = 303, $statusMessage = '', $prefixSystemLocale = TRUE) {
		$uri = $prefixSystemLocale ? DIRECTORY_SEPARATOR . (string)$this->locales->getSystemLocale() . $uri : $uri;
		throw new RedirectException($uri, $statusCode, $statusMessage, 1430730267);
	}
}
