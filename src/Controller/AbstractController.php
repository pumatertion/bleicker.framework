<?php

namespace Bleicker\Framework\Controller;

use Bleicker\Framework\ApplicationRequest;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Controller\Exception\AcceptedContentTypeNotSupportedException;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Response\ApplicationResponse;
use Bleicker\Response\ResponseInterface as ApplicationResponseInterface;
use Bleicker\View\Template\View;
use Bleicker\View\ViewInterface;

/**
 * Class AbstractController
 *
 * @package Bleicker\Framework\Controller
 */
abstract class AbstractController implements ControllerInterface {

	/**
	 * @var ApplicationRequest
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

	public function __construct() {
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
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
	 * @param ApplicationRequestInterface $request
	 * @return $this
	 */
	public function setRequest(ApplicationRequestInterface $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * @return ApplicationRequestInterface
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
}
