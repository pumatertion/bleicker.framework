<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\ApplicationRequestInterface;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\Http\RequestInterface;
use Bleicker\Framework\HttpApplicationRequest;

/**
 * Class WellformedApplicationRequestConverter
 *
 * @package Bleicker\Framework\Converter
 */
class WellformedApplicationRequestConverter extends AbstractTypeConverter implements WellformedApplicationRequestConverterInterface {

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
		$contentType = $source->getHeaders()->get('CONTENT_TYPE');
		if ($contentType === NULL || $contentType === 'application/x-www-form-urlencoded' || $contentType === 'text/html') {
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
		$contents = [];
		$content = $this->applicationRequest->getParentRequest()->getContent();
		parse_str($content, $contents);
		return $contents;
	}
}