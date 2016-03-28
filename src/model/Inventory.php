<?php
namespace happy\inventory\model;

use happy\inventory\AcquireMaterial;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\RegisterMaterial;

class Inventory {

    /**
     * Inventory constructor.
     */
    public function __construct() {
    }

    public function handleRegisterMaterial(RegisterMaterial $c) {
        return new MaterialRegistered($c->getName(), $c->getUnit());
    }

    public function handleAcquireMaterial(AcquireMaterial $c) {
        return new MaterialAcquired(
            $c->getMaterial(),
            $c->getAmount(),
            $c->getCost(),
            $c->getCurrency(),
            $c->getDocuments()
        );
    }
}