<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\HttpApplicationRequest;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\Framework\Http\Request;

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
	 * @param Request $source
	 * @param string $targetType
	 * @return boolean
	 */
	public static function canConvert($source = NULL, $targetType) {
		if (!($source instanceof Request)) {
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
