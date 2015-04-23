<?php
namespace Bleicker\Framework\Object;

use Bleicker\Framework\Object\TypeConverter\TypeConverterInterface;

/**
 * Class Converter
 *
 * @package Bleicker\Framework\Object
 */
interface ConverterInterface {

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return mixed
	 */
	public static function convert($source = NULL, $targetType);

	/**
	 * @param string $alias
	 * @param TypeConverterInterface $typeConverter
	 * @return void
	 */
	public static function registerTypeConverter($alias, TypeConverterInterface $typeConverter);

	/**
	 * @param string $alias
	 * @return void
	 */
	public static function unregisterTypeConverter($alias);

	/**
	 * @param string $alias
	 * @return TypeConverterInterface|NULL
	 */
	public static function getTypeConverter($alias);
}