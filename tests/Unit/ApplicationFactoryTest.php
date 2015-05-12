<?php

namespace Tests\Bleicker\Framework\Unit;

use Bleicker\Framework\ApplicationFactory;
use Tests\Bleicker\Framework\UnitTestCase;

/**
 * Class ApplicationFactoryTest
 *
 * @package Tests\Bleicker\Framework\Unit
 */
class ApplicationFactoryTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function httpApplicationTest(){
		$application = ApplicationFactory::http();
	}

}
