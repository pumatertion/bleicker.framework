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
 * Class Results
 *
 * @package Bleicker\Framework\Validation
 */
interface ResultCollectionInterface extends Countable, IteratorAggregate, ArrayAccess, Selectable {

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function filter(Closure $func);

	/**
	 * @return MessageInterface
	 */
	public function first();

	/**
	 * @return MessageInterface[]
	 */
	public function getResults();

	/**
	 * @param mixed $key
	 * @return $this
	 */
	public function removeKey($key);

	/**
	 * @param integer $offset
	 * @param integer $length
	 * @return MessageInterface[]
	 */
	public function slice($offset, $length = NULL);

	/**
	 * @param Closure $p
	 * @return boolean
	 */
	public function exists(Closure $p);

	/**
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * @param MessageInterface $result
	 * @return mixed
	 */
	public function indexOf(MessageInterface $result);

	/**
	 * @param Criteria $criteria
	 * @return static
	 */
	public function matching(Criteria $criteria);

	/**
	 * @return static
	 */
	public static function create();

	/**
	 * @param Closure $func
	 * @return boolean
	 */
	public function forAll(Closure $func);

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
	 * @return MessageInterface
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
	 * @return MessageInterface
	 */
	public function key();

	/**
	 * @return MessageInterface
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
	 * @return MessageInterface[]
	 */
	public function getValues();

	/**
	 * @param Closure $func
	 * @return MessageInterface[]
	 */
	public function partition(Closure $func);

	/**
	 * @param mixed $offset
	 * @param MessageInterface $result
	 * @return $this
	 */
	public function offsetSet($offset, $result);

	/**
	 * @return $this
	 */
	public function clear();

	/**
	 * @param mixed $offset
	 * @return MessageInterface
	 */
	public function offsetGet($offset);

	/**
	 * @param MessageInterface $value
	 * @return $this
	 */
	public function add(MessageInterface $value);

	/**
	 * @param mixed $result
	 * @return boolean
	 */
	public function contains(MessageInterface $result);

	/**
	 * @return MessageInterface
	 */
	public function next();

	/**
	 * @param mixed $key
	 * @param MessageInterface $value
	 * @return $this
	 */
	public function set($key, MessageInterface $value);

	/**
	 * @return integer
	 */
	public function count();

	/**
	 * @return MessageInterface
	 */
	public function last();

	/**
	 * @param MessageInterface $result
	 * @return $this
	 */
	public function remove(MessageInterface $result);
}