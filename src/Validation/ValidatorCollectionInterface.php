<?php
namespace Bleicker\Framework\Validation;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use IteratorAggregate;

/**
 * Class ValidatorCollection
 *
 * @package Bleicker\Framework\Validation
 */
interface ValidatorCollectionInterface extends ValidatorInterface, Countable, IteratorAggregate, ArrayAccess, Selectable {

	/**
	 * @param mixed $source
	 * @return $this
	 */
	public function validate($source = NULL);

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function filter(Closure $func);

	/**
	 * @return ValidatorInterface
	 */
	public function first();

	/**
	 * @param mixed $key
	 * @return $this
	 */
	public function removeKey($key);

	/**
	 * @param integer $offset
	 * @param integer $length
	 * @return ValidatorInterface[]
	 */
	public function slice($offset, $length = NULL);

	/**
	 * @param Closure $p
	 * @return boolean
	 */
	public function exists(Closure $p);

	/**
	 * @param ValidatorInterface $validator
	 * @return mixed
	 */
	public function indexOf(ValidatorInterface $validator);

	/**
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * @param Criteria $criteria
	 * @return static
	 */
	public function matching(Criteria $criteria);

	/**
	 * @param Closure $func
	 * @return boolean
	 */
	public function forAll(Closure $func);

	/**
	 * @return ValidatorInterface[]
	 */
	public function getValidators();

	/**
	 * @param mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset);

	/**
	 * @param int|string $key
	 * @return boolean
	 */
	public function containsKey($key);

	/**
	 * @param mixed $key
	 * @return ValidatorInterface
	 */
	public function get($key);

	/**
	 * @param mixed $offset
	 * @return $this
	 */
	public function offsetUnset($offset);

	/**
	 * @return ArrayIterator
	 */
	public function getIterator();

	/**
	 * @return ValidatorInterface
	 */
	public function key();

	/**
	 * @return ValidatorInterface
	 */
	public function current();

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function map(Closure $func);

	/**
	 * @return array
	 */
	public function getKeys();

	/**
	 * @return ValidatorInterface[]
	 */
	public function getValues();

	/**
	 * @param Closure $func
	 * @return ValidatorInterface[]
	 */
	public function partition(Closure $func);

	/**
	 * @param mixed $offset
	 * @param ValidatorInterface $validator
	 * @return $this
	 */
	public function offsetSet($offset, $validator);

	/**
	 * @return $this
	 */
	public function clear();

	/**
	 * @param mixed $offset
	 * @return ValidatorInterface
	 */
	public function offsetGet($offset);

	/**
	 * @param ValidatorInterface $value
	 * @return $this
	 */
	public function add(ValidatorInterface $value);

	/**
	 * @param mixed $validator
	 * @return boolean
	 */
	public function contains(ValidatorInterface $validator);

	/**
	 * @return ValidatorInterface
	 */
	public function next();

	/**
	 * @param mixed $key
	 * @param ValidatorInterface $value
	 * @return $this
	 */
	public function set($key, ValidatorInterface $value);

	/**
	 * @return integer
	 */
	public function count();

	/**
	 * @return ValidatorInterface
	 */
	public function last();

	/**
	 * @param ValidatorInterface $validator
	 * @return $this
	 */
	public function remove(ValidatorInterface $validator);
}