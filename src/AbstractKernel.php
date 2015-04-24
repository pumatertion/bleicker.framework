<?php

namespace Bleicker\Framework;
use Bleicker\Framework\Object\Converter;
use Bleicker\Framework\Object\TypeConverter\FloatTypeConverter;
use Bleicker\Framework\Object\TypeConverter\IntegerTypeConverter;
use Bleicker\Framework\Object\TypeConverter\StringTypeConverter;

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
