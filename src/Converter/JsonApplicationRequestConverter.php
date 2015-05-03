<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\RequestInterface;
use Bleicker\Framework\HttpApplicationRequest;

/**
 * Class JsonApplicationRequestConverter
 *
 * @package Bleicker\Framework\Converter
 */
class JsonApplicationRequestConverter implements JsonApplicationRequestConverterInterface {

	/**
	 * @var ApplicationRequestInterface
	 */
	protected $applicationRequest;

	/**
	 * @param RequestInterface $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if (!($source instanceof RequestInterface)) {
			return FALSE;
		}
		if ($targetType !== ApplicationRequestInterface::class) {
			return FALSE;
		}
		if ($source->getHeaders()->get('CONTENT_TYPE') !== 'application/json') {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param Request $source
	 * @return HttpApplicationRequest
	 */
	public function convert($source) {
		$this->applicationRequest = new HttpApplicationRequest($source);
		$this->applicationRequest
			->setHeaders($source->getHeaders()->all())
			->setParameters($source->getParameter()->all())
			->setContents($this->decodeContent());
		return $this->applicationRequest;
	}

	/**
	 * @return array
	 */
	public function decodeContent() {
		$content = $this->applicationRequest->getParentRequest()->getContent();
		return json_decode($content, TRUE);
	}
}
