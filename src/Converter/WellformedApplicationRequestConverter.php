<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Converter\AbstractTypeConverter;
use Bleicker\Framework\Http\Request;
use Bleicker\Framework\HttpApplicationRequest;
use Bleicker\Framework\HttpApplicationRequestInterface;

/**
 * Class WellformedApplicationRequestConverter
 *
 * @package Bleicker\Framework\Converter
 */
class WellformedApplicationRequestConverter extends AbstractTypeConverter implements WellformedApplicationRequestConverterInterface {

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
		$contents = [];
		$content = $this->applicationRequest->getParentRequest()->getContent();
		parse_str($content, $contents);
		return $contents;
	}
}
