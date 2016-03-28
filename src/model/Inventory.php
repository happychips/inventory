<?php
namespace happy\inventory\model;

use happy\inventory\AcquireMaterial;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\RegisterMaterial;

class Inventory {
    /** @var Session */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function handleRegisterMaterial(RegisterMaterial $c) {
        return new MaterialRegistered($c->getName(), $c->getUnit(), $this->session->requireLogin(), $c->getWhen());
    }

    public function handleAcquireMaterial(AcquireMaterial $c) {
        return new MaterialAcquired(
            $c->getMaterial(),
            $c->getAmount(),
            $c->getCost(),
            $c->getCurrency(),
            $c->getDocuments(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }
}