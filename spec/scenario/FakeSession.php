<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\model\Session;
use happy\inventory\model\UserIdentifier;

class FakeSession implements Session {

    private $login;

    /**
     * @return UserIdentifier
     * @throws \Exception
     */
    public function requireLogin() {
        if ($this->login) {
            return $this->login;
        }
        throw new \Exception('Access denied.');
    }

    public function login(UserIdentifier $user) {
        $this->login = $user;
    }
}