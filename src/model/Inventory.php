<?php
namespace happy\inventory\model;

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
}