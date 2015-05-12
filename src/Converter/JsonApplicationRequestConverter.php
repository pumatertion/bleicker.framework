<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\RequestInterface;
use Bleicker\Framework\HttpHttpApplicationRequest;

/**
 * Class JsonApplicationRequestConverter
 *
 * @package Bleicker\Framework\Converter
 */
class JsonApplicationRequestConverter extends AbstractTypeConverter implements JsonApplicationRequestConverterInterface {

	/**
	 * @var HttpApplicationRequestInterface
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
		if ($targetType !== HttpApplicationRequestInterface::class) {
			return FALSE;
		}
		if ($source->getHeaders()->get('CONTENT_TYPE') === 'application/json') {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param Request $source
	 * @return HttpHttpApplicationRequest
	 */
	public function convert($source) {
		$this->applicationRequest = new HttpHttpApplicationRequest($source);
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
