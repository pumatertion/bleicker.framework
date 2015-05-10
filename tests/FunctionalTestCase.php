<?php

namespace Tests\Bleicker\Framework;

use Bleicker\FastRouter\Router;
use Bleicker\Framework\Converter\WellformedApplicationRequestConverter;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Routing\RouterInterface;
use Bleicker\Translation\Locale;
use Bleicker\Translation\Locales;
use Doctrine\ORM\Tools\Setup;

/**
 * Class FunctionalTestCase
 *
 * @package Tests\Bleicker\Framework
 */
class FunctionalTestCase extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		WellformedApplicationRequestConverter::register();
		Locale::register('English', 'en', 'GB');
		ObjectManager::prune();
		ObjectManager::add(EntityManagerInterface::class, function () {
			$entityManager = EntityManager::create(
				['driver' => 'pdo_sqlite', 'path' => __DIR__ . '/db.sqlite'],
				Setup::createYAMLMetadataConfiguration([__DIR__], TRUE)
			);
			ObjectManager::add(EntityManagerInterface::class, $entityManager, TRUE);
			return $entityManager;
		});
		ObjectManager::add(RouterInterface::class, Router::getInstance(__DIR__ . '/route.cache.php', TRUE));
	}

	protected function tearDown() {
		parent::tearDown();
		Locales::prune();
		ObjectManager::prune();
	}
}
