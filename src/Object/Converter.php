<?php

namespace Bleicker\Framework\Object;

use Bleicker\Framework\Object\Exception\MultipleTypeConvertersFoundException;
use Bleicker\Framework\Object\Exception\NoTypeConverterFoundException;
use Bleicker\Framework\Object\TypeConverter\TypeConverterInterface;
use Closure;

/**
 * Class Converter
 *
 * @package Bleicker\Framework\Object
 */
class Converter implements ConverterInterface {

	protected static $typeConverters = [];

	/**
	 * @param string $alias
	 * @param TypeConverterInterface $typeConverter
	 * @return void
	 */
	public static function register($alias, TypeConverterInterface $typeConverter) {
		static::$typeConverters[$alias] = $typeConverter;
	}

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function unregister($alias) {
		if (array_key_exists($alias, static::$typeConverters)) {
			unset(static::$typeConverters[$alias]);
		}
	}

	/**
	 * @param string $alias
	 * @return TypeConverterInterface|NULL
	 */
	public static function get($alias) {
		if (array_key_exists($alias, static::$typeConverters)) {
			return static::$typeConverters[$alias];
		}
		return NULL;
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
		return array_filter(static::$typeConverters, static::getTypeConverterMatchingClosure($source, $targetType));
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

	/**
	 * @return void
	 */
	public static function prune() {
		static::$typeConverters = [];
	}
}
