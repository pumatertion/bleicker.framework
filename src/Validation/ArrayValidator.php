<?php

namespace Bleicker\Framework\Validation;

use ArrayAccess;
use ArrayIterator;
use Bleicker\Framework\Utility\Arrays;
use Closure;
use Countable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Doctrine\Common\Collections\Selectable;
use IteratorAggregate;

/**
 * Class PropertyValidator
 *
 * @package Bleicker\Framework\Validation
 */
class ArrayValidator extends AbstractValidator implements ValidatorInterface, Countable, IteratorAggregate, ArrayAccess, Selectable {

	/**
	 * @var ValidatorCollection[]
	 */
	protected $validators = [];

	/**
	 * @param ValidatorCollection[] $validators
	 */
	public function __construct(array $validators = []) {
		parent::__construct();
		$this->validators = $validators;
	}

	/**
	 * @param $propertyPath
	 * @param ValidatorInterface $validator
	 * @return $this
	 */
	public function addValidatorForPropertyPath($propertyPath, ValidatorInterface $validator) {
		if (!$this->offsetExists($propertyPath)) {
			$validatorCollection = new ValidatorCollection();
			$this->offsetSet($propertyPath, $validatorCollection);
		}
		$this->offsetGet($propertyPath)->add($validator);
		return $this;
	}

	/**
	 * @param mixed $source
	 * @return $this
	 */
	public function validate($source = NULL) {
		$source = (array)$source;
		while ($validator = $this->current()) {
			$results = $validator->validate(Arrays::getValueByPath($source, $this->key()))->getResults();
			foreach ($results->getResults() as $result) {
				$this->getResults()->add($result->setPropertyPath($this->key()));
			}
			$this->next();
		}
		return $this;
	}

	/**
	 * @return ValidatorCollection[]
	 */
	public function getValidators() {
		return $this->validators;
	}

	/**
	 * @return ValidatorCollection
	 */
	public function first() {
		return reset($this->validators);
	}

	/**
	 * @return ValidatorCollection
	 */
	public function last() {
		return end($this->validators);
	}

	/**
	 * @return ValidatorCollection
	 */
	public function key() {
		return key($this->validators);
	}

	/**
	 * @return ValidatorCollection
	 */
	public function next() {
		return next($this->validators);
	}

	/**
	 * @return ValidatorCollection
	 */
	public function current() {
		return current($this->validators);
	}

	/**
	 * @param string $propertyPath
	 * @return $this
	 */
	public function removePropertyPath($propertyPath) {
		if (isset($this->validators[$propertyPath]) || array_key_exists($propertyPath, $this->validators)) {
			return $this->remove($this->validators[$propertyPath]);
		}
		return $this;
	}

	/**
	 * @param ValidatorCollection $validator
	 * @return $this
	 */
	public function remove(ValidatorCollection $validator) {
		$key = array_search($validator, $this->validators, TRUE);
		if ($key !== FALSE) {
			unset($this->validators[$key]);
		}
		return $this;
	}

	/**
	 * @param string $propertyPath
	 * @return boolean
	 */
	public function offsetExists($propertyPath) {
		return $this->containsKey($propertyPath);
	}

	/**
	 * @param string $propertyPath
	 * @return ValidatorCollection
	 */
	public function offsetGet($propertyPath) {
		return $this->get($propertyPath);
	}

	/**
	 * @param string $propertyPath
	 * @param ValidatorCollection $validator
	 * @return $this
	 */
	public function offsetSet($propertyPath, $validator) {
		return $this->set($propertyPath, $validator);
	}

	/**
	 * @param string $propertyPath
	 * @return $this
	 */
	public function offsetUnset($propertyPath) {
		return $this->remove($propertyPath);
	}

	/**
	 * @param string $propertyPath
	 * @return boolean
	 */
	public function containsKey($propertyPath) {
		return isset($this->validators[$propertyPath]) || array_key_exists($propertyPath, $this->validators);
	}

	/**
	 * @param mixed $validator
	 * @return boolean
	 */
	public function contains(ValidatorCollection $validator) {
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
	 * @param ValidatorCollection $validator
	 * @return mixed
	 */
	public function indexOf(ValidatorCollection $validator) {
		return array_search($validator, $this->validators, TRUE);
	}

	/**
	 * @param mixed $propertyPath
	 * @return ValidatorCollection
	 */
	public function get($propertyPath) {
		if (isset($this->validators[$propertyPath])) {
			return $this->validators[$propertyPath];
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
	 * @return MessageInterface[]
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
	 * @param mixed $propertyPath
	 * @param ValidatorCollection $value
	 * @return $this
	 */
	public function set($propertyPath, ValidatorCollection $value) {
		$this->validators[$propertyPath] = $value;
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
	 * @return ValidatorCollection[]
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
	 * @return ValidatorCollection[]
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
