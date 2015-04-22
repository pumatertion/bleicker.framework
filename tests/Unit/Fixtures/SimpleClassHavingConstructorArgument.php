<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures;

/**
 * Class SimpleClassHavingConstructorArgument
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures
 */
class SimpleClassHavingConstructorArgument {

	protected $title;

	/**
	 * @param mixed $title
	 */
	public function __construct($title = NULL) {
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

}
