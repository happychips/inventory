<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\CostumerAdded;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\ProductRegistered;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\Identifier;
use happy\inventory\model\Inventory;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;
use watoki\karma\testing\Specification as KarmaSpecification;

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

    public function IRegisteredTheMaterial_WithTheUnit($material, $unit) {
        $this->karma->given(new MaterialRegistered(
            MaterialIdentifier::fromNameAndUnit($material, $unit),
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

    public function IReceivedTheDeliveryOf__Partially($amount, $material) {
        $this->IReceivedTheDeliveryOf($amount, $material, true);
    }

    public function IReceivedTheDeliveryOf($amount, $material, $partial = false) {
        $this->karma->given(new DeliveryReceived(
            new AcquisitionIdentifier($amount . $material),
            $partial,
            null,
            [],
            [],
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IAddedACostumer($name) {
        $this->karma->given(new CostumerAdded(
            CostumerIdentifier::fromName($name),
            $name,
            new UserIdentifier('foo')
        ), Inventory::IDENTIFIER);
    }

    public function IRegisteredTheProduct_WithTheUnit($name, $unit) {
        $this->karma->given(new ProductRegistered(
            ProductIdentifier::fromNameAndUnit($name, $unit),
            $name,
            $unit,
            new UserIdentifier('nobody')
        ), Inventory::IDENTIFIER);
    }

    public function theNextGeneratedIdentifierIs($string) {
        Identifier::$next = $string;
    }
}