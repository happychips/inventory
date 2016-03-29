<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\Inventory;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;
use watoki\karma\Specification as KarmaSpecification;

class Context {

    /** @var KarmaSpecification */
    public $karma;
    /** @var FakeSession */
    private $session;

    /**
     * @param KarmaSpecification $karma
     * @param FakeSession $session
     */
    public function __construct(KarmaSpecification $karma, FakeSession $session) {
        $this->session = $session;
        $this->karma = $karma;
    }

    public function IAmLoggedInAs($user) {
        $this->session->login(new UserIdentifier($user));
    }

    public function NowIs($when) {
        Time::freeze(new \DateTimeImmutable($when));
    }

    public function nothingHasHappened() {
        $this->karma->reset();
    }

    public function IRegisteredTheMaterial_WithTheUnit($material, $unit) {
        $this->karma->given(new MaterialRegistered(
            $material,
            $unit,
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IAcquired_Of($amount, $material) {
        $this->karma->given(new MaterialAcquired(
            new AcquisitionIdentifier($amount . $material),
            new MaterialIdentifier($material),
            $amount,
            new Money(0, 'FOO'),
            [],
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IReceivedTheDeliveryOf($amount, $material) {
        $this->karma->given(new DeliveryReceived(
            new AcquisitionIdentifier($amount . $material),
            null,
            [],
            [],
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IAddedACostumer($name) {
    }

    public function IRegisteredTheProduct_WithTheUnit($name, $unit) {
    }
}