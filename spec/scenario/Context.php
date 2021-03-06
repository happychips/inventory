<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\ConsumeMaterial;
use happy\inventory\events\CostumerAdded;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\LinkedConsumptionsSet;
use happy\inventory\events\MaterialCategorySet;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\MaterialsAcquired;
use happy\inventory\events\ProductDelivered;
use happy\inventory\events\ProductProduced;
use happy\inventory\events\ProductRegistered;
use happy\inventory\events\StockUpdated;
use happy\inventory\events\SupplierAdded;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\DeviantAmount;
use happy\inventory\model\Identifier;
use happy\inventory\model\Inventory;
use happy\inventory\model\MaterialAcquisition;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;
use watoki\karma\testing\Specification as Karma;

class Context {

    /** @var Karma */
    public $karma;
    /** @var FakeSession */
    private $session;

    /**
     * @param Karma $karma
     * @param FakeSession $session
     */
    public function __construct(Karma $karma, FakeSession $session) {
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
        $this->IAcquired([[$amount, $material]]);
    }

    public function IAcquired($amountsAndMaterials) {
        $this->karma->given(new MaterialsAcquired(
            new AcquisitionIdentifier(implode('', $amountsAndMaterials[0])),
            array_map(function ($amountAndMatieral) {
                list($amount, $material) = $amountAndMatieral;

                return new MaterialAcquisition(
                    MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT),
                    $amount,
                    new Money(0, 'FOO')
                );
            }, $amountsAndMaterials),
            null,
            [],
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IReceived_OfTheDeliveryOf($deliveredAmount, $amount, $material) {
        $this->IReceivedTheDeliveryOf($amount, $material, true, $deliveredAmount);
    }

    public function IReceivedTheDeliveryOf__Partially($amount, $material) {
        $this->IReceivedTheDeliveryOf($amount, $material, true);
    }

    public function IReceivedTheDeliveryOf($amount, $material, $partial = false, $deliveredAmount = null) {
        $this->karma->given(new DeliveryReceived(
            new AcquisitionIdentifier($amount . $material),
            $partial,
            [new DeviantAmount(
                MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT),
                $deliveredAmount
            )],
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

    public function IAddedASupplier($name) {
        $this->karma->given(new SupplierAdded(
            SupplierIdentifier::fromName($name),
            $name,
            new UserIdentifier('test')
        ), Inventory::IDENTIFIER);
    }

    public function IConsumed__Of($amount, $unit, $material) {
        $this->karma->given(new MaterialConsumed(
            MaterialIdentifier::fromNameAndUnit($material, $unit),
            $amount,
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function IUpdatedTheInventoryOf_To($material, $amount, $unit) {
        $this->karma->given(new InventoryUpdated(
            MaterialIdentifier::fromNameAndUnit($material, $unit),
            $amount,
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function IProduced__Of($amount, $units, $product) {
        $this->karma->given(new ProductProduced(
            ProductIdentifier::fromNameAndUnit($product, $units),
            $amount,
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function IDelivered__Of($amount, $unit, $product) {
        $this->karma->given(new ProductDelivered(
            ProductIdentifier::fromNameAndUnit($product, $unit),
            $amount,
            CostumerIdentifier::fromName('foo'),
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function IUpdatedTheStockOf_To($product, $amount, $unit) {
        $this->karma->given(new StockUpdated(
            ProductIdentifier::fromNameAndUnit($product, $unit),
            $amount,
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function ISetTheConsumptions_For(array $consumptions, $product) {
        $this->karma->given(new LinkedConsumptionsSet(
            new ProductIdentifier($product),
            array_map(function ($consumption) {
                return new ConsumeMaterial(new MaterialIdentifier($consumption[1]), $consumption[0]);
            }, $consumptions),
            new UserIdentifier('me')
        ), Inventory::IDENTIFIER);
    }

    public function IPut_Into($material, $category) {
        $this->karma->given(new MaterialCategorySet(
            MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT),
            $category,
            new UserIdentifier('test')
        ));
    }
}