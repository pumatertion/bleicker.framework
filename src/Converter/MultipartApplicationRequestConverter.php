<?php

namespace Bleicker\Framework\Converter;

use Bleicker\Framework\Http\Request;
use Bleicker\Framework\HttpApplicationRequestInterface;

/**
 * Class MultipartApplicationRequestConverter
 *
 * @package Bleicker\Framework\Converter
 */
class MultipartApplicationRequestConverter extends WellformedApplicationRequestConverter implements MultipartApplicationRequestConverterInterface {

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
		if (preg_match('/boundary=(.*)$/', $source->getHeaders()->get('CONTENT_TYPE'))) {
			return TRUE;
		}
		return FALSE;
	}
}
