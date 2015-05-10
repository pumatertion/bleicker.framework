<?php

namespace Tests\Bleicker\Framework\Functional\Fixtures;

use Bleicker\Framework\Controller\AbstractController;

/**
 * Class SecuredController
 *
 * @package Tests\Bleicker\Framework\Functional\Fixtures
 */
class SecuredController extends AbstractController {

	/**
	 * @return string
	 */
	public function indexAction() {
		return 'foo';
	}
}
