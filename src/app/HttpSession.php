<?php
namespace happy\inventory\app;

use happy\inventory\model\Session;
use happy\inventory\model\UserIdentifier;

class HttpSession implements Session {

    public function __construct() {
        session_start();
    }

    /**
     * @return UserIdentifier
     * @throws \Exception
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            throw new \Exception('Access denied.');
        }
        return new UserIdentifier($_SESSION['user']);
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']) && $_SESSION['user'];
    }

    public function login($user) {
        $_SESSION['user'] = $user;
    }

    public function logout() {
        unset($_SESSION['user']);
    }
}