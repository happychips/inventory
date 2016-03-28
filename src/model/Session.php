<?php
namespace happy\inventory\model;

interface Session {

    /**
     * @return UserIdentifier
     * @throws \Exception
     */
    public function requireLogin();
}