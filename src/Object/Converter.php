<?php

namespace Bleicker\Framework\Object;

use Bleicker\Framework\Object\Exception\MultipleTypeConvertersFoundException;
use Bleicker\Framework\Object\Exception\NoTypeConverterFoundException;
use Bleicker\Framework\Object\TypeConverter\TypeConverterInterface;
use Bleicker\Framework\Registry;
use Closure;

/**
 * Class Converter
 *
 * @package Bleicker\Framework\Object
 */
class Converter implements ConverterInterface {

	const REGISTRATION_PATH = 'typeConverter';

	/**
	 * @param string $alias
	 * @param TypeConverterInterface $typeConverter
	 * @return void
	 */
	public static function registerTypeConverter($alias, TypeConverterInterface $typeConverter) {
		$availableTypeConverters = static::getTypeConverters();
		$availableTypeConverters[$alias] = $typeConverter;
		Registry::add(static::REGISTRATION_PATH, $availableTypeConverters);
	}

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function unregisterTypeConverter($alias) {
		$availableTypeConverters = static::getTypeConverters();
		if (array_key_exists($alias, $availableTypeConverters)) {
			unset($availableTypeConverters[$alias]);
		}
		Registry::add(static::REGISTRATION_PATH, $availableTypeConverters);
	}

	/**
	 * @param string $alias
	 * @return TypeConverterInterface|NULL
	 */
	public static function getTypeConverter($alias) {
		$availableTypeConverters = static::getTypeConverters();
		if (array_key_exists($alias, $availableTypeConverters)) {
			return $availableTypeConverters[$alias];
		}
		return NULL;
	}

	/**
	 * @return array
	 */
	protected static function getTypeConverters() {
		$availableTypeConverters = Registry::get(static::REGISTRATION_PATH);
		if (is_array($availableTypeConverters)) {
			return $availableTypeConverters;
		}
		return [];
	}

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return mixed
	 * @throws Exception\MultipleTypeConvertersFoundException
	 * @throws Exception\NoTypeConverterFoundException
	 */
	public static function convert($source = NULL, $targetType) {
		$possibleTypeConverters = static::resolveMatchingTypeConverter($source, $targetType);
		if (count($possibleTypeConverters) === 0) {
			throw new NoTypeConverterFoundException('Could not find any suitable TypeConverter to convert "' . gettype($source) . '" to "' . $targetType . '"', 1429829310);
		}
		if (count($possibleTypeConverters) > 1) {
			throw new MultipleTypeConvertersFoundException('Multiple suitable TypeConverters found. Can\'t decide which one to use for converting  from "' . gettype($source) . '" to "' . $targetType . '"', 1429829311);
		}
		/** @var TypeConverterInterface $typeConverter */
		$typeConverter = array_shift($possibleTypeConverters);
		return $typeConverter->convert($source);
	}

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return array
	 */
	protected static function resolveMatchingTypeConverter($source = NULL, $targetType) {
		return array_filter(static::getTypeConverters(), static::getTypeConverterMatchingClosure($source, $targetType));
	}

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return Closure
	 */
	protected static function getTypeConverterMatchingClosure($source = NULL, $targetType) {
		return function (TypeConverterInterface $typeConverter) use ($source, $targetType) {
			return $typeConverter::canConvert($source, $targetType);
		};
	}
}
