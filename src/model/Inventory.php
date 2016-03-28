<?php
namespace happy\inventory\model;

use happy\inventory\AcquireMaterial;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\ReceiveDelivery;
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
        return new MaterialRegistered(
            $c->getName(),
            $c->getUnit(),
            $this->session->requireLogin(),
            $c->getWhen());
    }

    public function handleAcquireMaterial(AcquireMaterial $c) {
        return new MaterialAcquired(
            $c->getMaterial(),
            $c->getAmount(),
            $c->getCost(),
            $c->getDocuments(),
            $this->session->requireLogin(),
            $c->getWhen());
    }

    public function handleReceiveDelivery(ReceiveDelivery $c) {
        return new DeliveryReceived(
            $c->getAcquisition(),
            $c->getAmount(),
            $c->getDocuments(),
            $c->getExtraCosts(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }
}