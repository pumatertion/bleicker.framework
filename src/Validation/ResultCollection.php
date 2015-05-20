<?php

namespace Bleicker\Framework\Validation;

use ArrayIterator;
use Closure;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use ReflectionClass;

/**
 * Class ResultCollection
 *
 * @package Bleicker\Framework\Validation
 */
class ResultCollection implements ResultCollectionInterface {

	/**
	 * @var MessageInterface[]
	 */
	protected $results = [];

	/**
	 * @param MessageInterface[] $results
	 */
	public function __construct(array $results = []) {
		$this->results = $results;
	}

	/**
	 * @param MessageInterface[] $results
	 * @return static
	 */
	public static function create(array $results = []) {
		$reflection = new ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
	}

	/**
	 * @return MessageInterface[]
	 */
	public function getResults() {
		return $this->results;
	}

	/**
	 * @return MessageInterface
	 */
	public function first() {
		return reset($this->results);
	}

	/**
	 * @return MessageInterface
	 */
	public function last() {
		return end($this->results);
	}

	/**
	 * @return MessageInterface
	 */
	public function key() {
		return key($this->results);
	}

	/**
	 * @return MessageInterface
	 */
	public function next() {
		return next($this->results);
	}

	/**
	 * @return MessageInterface
	 */
	public function current() {
		return current($this->results);
	}

	/**
	 * @param mixed $key
	 * @return $this
	 */
	public function removeKey($key) {
		if (isset($this->results[$key]) || array_key_exists($key, $this->results)) {
			return $this->remove($this->results[$key]);
		}
		return $this;
	}

	/**
	 * @param MessageInterface $result
	 * @return $this
	 */
	public function remove(MessageInterface $result) {
		$key = array_search($result, $this->results, TRUE);
		if ($key !== FALSE) {
			unset($this->results[$key]);
		}
		return $this;
	}

	/**
	 * @param mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return $this->containsKey($offset);
	}

	/**
	 * @param mixed $offset
	 * @return MessageInterface
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * @param mixed $offset
	 * @param MessageInterface $result
	 * @return $this
	 */
	public function offsetSet($offset, $result) {
		return $this->set($offset, $result);
	}

	/**
	 * @param mixed $offset
	 * @return $this
	 */
	public function offsetUnset($offset) {
		return $this->remove($offset);
	}

	/**
	 * @param int|string $key
	 * @return boolean
	 */
	public function containsKey($key) {
		return isset($this->results[$key]) || array_key_exists($key, $this->results);
	}

	/**
	 * @param mixed $result
	 * @return boolean
	 */
	public function contains(MessageInterface $result) {
		return in_array($result, $this->results, TRUE);
	}

	/**
	 * @param Closure $p
	 * @return boolean
	 */
	public function exists(Closure $p) {
		foreach ($this->results as $key => $result) {
			if ($p($key, $result)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param MessageInterface $result
	 * @return mixed
	 */
	public function indexOf(MessageInterface $result) {
		return array_search($result, $this->results, TRUE);
	}

	/**
	 * @param mixed $key
	 * @return MessageInterface
	 */
	public function get($key) {
		if (isset($this->results[$key])) {
			return $this->results[$key];
		}
		return NULL;
	}

	/**
	 * @return array
	 */
	public function getKeys() {
		return array_keys($this->results);
	}

	/**
	 * @return MessageInterface[]
	 */
	public function getValues() {
		return array_values($this->results);
	}

	/**
	 * @return integer
	 */
	public function count() {
		return count($this->results);
	}

	/**
	 * @param mixed $key
	 * @param MessageInterface $value
	 * @return $this
	 */
	public function set($key, MessageInterface $value) {
		$this->results[$key] = $value;
		return $this;
	}

	/**
	 * @param MessageInterface $value
	 * @return $this
	 */
	public function add(MessageInterface $value) {
		$this->results[] = $value;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return !$this->results;
	}

	/**
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->results);
	}

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function map(Closure $func) {
		return new static(array_map($func, $this->results));
	}

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function filter(Closure $func) {
		return new static(array_filter($this->results, $func));
	}

	/**
	 * @param Closure $func
	 * @return boolean
	 */
	public function forAll(Closure $func) {
		foreach ($this->results as $key => $result) {
			if (!$func($key, $result)) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * @param Closure $func
	 * @return MessageInterface[]
	 */
	public function partition(Closure $func) {
		$coll1 = $coll2 = array();
		foreach ($this->results as $key => $result) {
			if ($func($key, $result)) {
				$coll1[$key] = $result;
			} else {
				$coll2[$key] = $result;
			}
		}
		return array(new static($coll1), new static($coll2));
	}

	/**
	 * @return $this
	 */
	public function clear() {
		$this->results = [];
		return $this;
	}

	/**
	 * @param integer $offset
	 * @param integer $length
	 * @return MessageInterface[]
	 */
	public function slice($offset, $length = NULL) {
		return array_slice($this->results, $offset, $length, TRUE);
	}

	/**
	 * @param Criteria $criteria
	 * @return static
	 */
	public function matching(Criteria $criteria) {
		$expr = $criteria->getWhereExpression();
		$filtered = $this->results;

		if ($expr) {
			$visitor = new ClosureExpressionVisitor();
			$filter = $visitor->dispatch($expr);
			$filtered = array_filter($filtered, $filter);
		}

		if ($orderings = $criteria->getOrderings()) {
			$next = NULL;
			foreach (array_reverse($orderings) as $field => $ordering) {
				$next = ClosureExpressionVisitor::sortByField($field, $ordering == 'DESC' ? -1 : 1, $next);
			}

			usort($filtered, $next);
		}

		$offset = $criteria->getFirstResult();
		$length = $criteria->getMaxResults();

		if ($offset || $length) {
			$filtered = array_slice($filtered, (int)$offset, $length);
		}

		return new static($filtered);
	}
}
