<?php

namespace Bleicker\Framework;
use Bleicker\Converter\Converter;
use Bleicker\Converter\TypeConverter\FloatTypeConverter;
use Bleicker\Converter\TypeConverter\IntegerTypeConverter;
use Bleicker\Converter\TypeConverter\StringTypeConverter;

/**
 * Class Kernel
 *
 * @package Bleicker\Framework
 */
abstract class AbstractKernel implements KernelInterface {

	public function __construct() {
		Converter::register(IntegerTypeConverter::class, new IntegerTypeConverter());
		Converter::register(FloatTypeConverter::class, new FloatTypeConverter());
		Converter::register(StringTypeConverter::class, new StringTypeConverter());
	}
}
