<?php

namespace Tests\Bleicker\Framework\Unit\Fixtures;

use Bleicker\Account\Account;
use Bleicker\Account\Role;
use Bleicker\Framework\HttpApplicationRequestInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Token\AbstractToken;

/**
 * Class AuthKeyToken
 *
 * @package Tests\Bleicker\Framework\Unit\Fixtures
 */
class AuthKeyToken extends AbstractToken {

	/**
	 * @var HttpApplicationRequestInterface
	 */
	protected $request;

	/**
	 * @return $this
	 */
	protected function initialize() {
		$this->request = ObjectManager::get(HttpApplicationRequestInterface::class);
		return parent::initialize();
	}

	/**
	 * @return $this
	 */
	public function injectCredential() {
		$this->getCredential()->setValue($this->request->getParameter('authKey'));
		return $this;
	}

	/**
	 * @return $this
	 */
	public function fetchAndSetAccount() {
		if ($this->getCredential()->getValue() === '123456789') {
			$account = new Account();
			$account->addRole(new Role('Guest'));
			$this->getCredential()->setAccount($account);
		}
		return $this;
	}
}
