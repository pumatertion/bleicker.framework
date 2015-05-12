<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures;

use Bleicker\Framework\Controller\AbstractController;

/**
 * Class SimpleController
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures
 */
class SimpleController extends AbstractController {

	/**
	 * @return string
	 */
	public function indexAction() {
		return 'Hello world';
	}
}
