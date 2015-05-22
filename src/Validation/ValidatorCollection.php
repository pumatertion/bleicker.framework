<?php

namespace Bleicker\Framework\Validation;

use ArrayIterator;
use Closure;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use ReflectionClass;

/**
 * Class ValidatorCollection
 *
 * @package Bleicker\Framework\Validation
 */
class ValidatorCollection extends AbstractValidator implements ValidatorCollectionInterface {

	/**
	 * @var ValidatorInterface[]
	 */
	protected $validators;

	/**
	 * @param ValidatorInterface[] $validators
	 */
	public function __construct(array $validators = []) {
		parent::__construct();
		$this->validators = $validators;
	}

	/**
	 * @param ValidatorInterface[] $validators
	 * @return static
	 */
	public static function create(array $validators = []) {
		$reflection = new ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
	}

	/**
	 * @param mixed $source
	 * @return $this
	 */
	public function validate($source = NULL) {
		foreach ($this->validators as $validator) {
			$results = $validator->validate($source)->getResults();
			foreach ($results as $result) {
				$this->getResults()->add($result);
			}
		}
		return $this;
	}

	/**
	 * @return ValidatorInterface[]
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @return ValidatorInterface
	 */
	public function first() {
		return reset($this->validators);
	}

	/**
	 * @return ValidatorInterface
	 */
	public function last() {
		return end($this->validators);
	}

	/**
	 * @return ValidatorInterface
	 */
	public function key() {
		return key($this->validators);
	}

	/**
	 * @return ValidatorInterface
	 */
	public function next() {
		return next($this->validators);
	}

	/**
	 * @return ValidatorInterface
	 */
	public function current() {
		return current($this->validators);
	}

	/**
	 * @param mixed $key
	 * @return $this
	 */
	public function removeKey($key) {
		if (isset($this->validators[$key]) || array_key_exists($key, $this->validators)) {
			return $this->remove($this->validators[$key]);
		}
		return $this;
	}

	/**
	 * @param ValidatorInterface $validator
	 * @return $this
	 */
	public function remove(ValidatorInterface $validator) {
		$key = array_search($validator, $this->validators, TRUE);
		if ($key !== FALSE) {
			unset($this->validators[$key]);
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
	 * @return ValidatorInterface
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	/**
	 * @param mixed $offset
	 * @param ValidatorInterface $validator
	 * @return $this
	 */
	public function offsetSet($offset, $validator) {
		return $this->set($offset, $validator);
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
		return isset($this->validators[$key]) || array_key_exists($key, $this->validators);
	}

	/**
	 * @param mixed $validator
	 * @return boolean
	 */
	public function contains(ValidatorInterface $validator) {
		return in_array($validator, $this->validators, TRUE);
	}

	/**
	 * @param Closure $p
	 * @return boolean
	 */
	public function exists(Closure $p) {
		foreach ($this->validators as $key => $validator) {
			if ($p($key, $validator)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param ValidatorInterface $validator
	 * @return mixed
	 */
	public function indexOf(ValidatorInterface $validator) {
		return array_search($validator, $this->validators, TRUE);
	}

	/**
	 * @param mixed $key
	 * @return ValidatorInterface
	 */
	public function get($key) {
		if (isset($this->validators[$key])) {
			return $this->validators[$key];
		}
		return NULL;
	}

	/**
	 * @return array
	 */
	public function getKeys() {
		return array_keys($this->validators);
	}

	/**
	 * @return ValidatorInterface[]
	 */
	public function getValues() {
		return array_values($this->validators);
	}

	/**
	 * @return integer
	 */
	public function count() {
		return count($this->validators);
	}

	/**
	 * @param mixed $key
	 * @param ValidatorInterface $value
	 * @return $this
	 */
	public function set($key, ValidatorInterface $value) {
		$this->validators[$key] = $value;
		return $this;
	}

	/**
	 * @param ValidatorInterface $value
	 * @return $this
	 */
	public function add(ValidatorInterface $value) {
		$this->validators[] = $value;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return !$this->validators;
	}

	/**
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->validators);
	}

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function map(Closure $func) {
		return new static(array_map($func, $this->validators));
	}

	/**
	 * @param Closure $func
	 * @return static
	 */
	public function filter(Closure $func) {
		return new static(array_filter($this->validators, $func));
	}

	/**
	 * @param Closure $func
	 * @return boolean
	 */
	public function forAll(Closure $func) {
		foreach ($this->validators as $key => $validator) {
			if (!$func($key, $validator)) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * @param Closure $func
	 * @return ValidatorInterface[]
	 */
	public function partition(Closure $func) {
		$coll1 = $coll2 = array();
		foreach ($this->validators as $key => $validator) {
			if ($func($key, $validator)) {
				$coll1[$key] = $validator;
			} else {
				$coll2[$key] = $validator;
			}
		}
		return array(new static($coll1), new static($coll2));
	}

	/**
	 * @return $this
	 */
	public function clear() {
		$this->validators = [];
		return $this;
	}

	/**
	 * @param integer $offset
	 * @param integer $length
	 * @return ValidatorInterface[]
	 */
	public function slice($offset, $length = NULL) {
		return array_slice($this->validators, $offset, $length, TRUE);
	}

	/**
	 * @param Criteria $criteria
	 * @return static
	 */
	public function matching(Criteria $criteria) {
		$expr = $criteria->getWhereExpression();
		$filtered = $this->validators;

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
