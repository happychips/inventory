<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\AcquireMaterial;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\RegisterMaterial;
use rtens\domin\parameters\file\MemoryFile;
use watoki\karma\Specification;

class Action {

    /** @var Specification */
    private $karma;
    /** @var \DateTimeImmutable */
    private $when;

    /**
     * @param Specification $karma
     */
    public function __construct(Specification $karma) {
        $this->karma = $karma;
    }

    public function IRegisterAMaterial_WithTheUnit($material, $unit) {
        $this->karma->when(new RegisterMaterial($material, $unit, $this->when));
    }

    public function IRegisterAProduct_WithTheUnit($product, $unit) {
    }

    public function IAcquire_UnitsOf_For($amount, $material, $cost, $currency) {
        $this->karma->when(new AcquireMaterial(
            new MaterialIdentifier($material),
            intval($amount),
            intval($cost * 100),
            $currency,
            [],
            $this->when
        ));
    }

    public function IAcquire_UnitsOf_WithTheDocuments($amount, $material, $documents) {
        $this->karma->when(new AcquireMaterial(
            new MaterialIdentifier($material),
            intval($amount),
            4200,
            'foo',
            array_map(function ($document) {
                return new MemoryFile($document, 'type/foo', $document . 'content');
            }, $documents)
        ));
    }

    public function IReceiveTheDeliveryOf($acquisition) {
    }

    public function IReceiveTheDeliveryOf_WithTheDocuments_Attached($acquisition, $documents) {
    }

    public function IReceiveTheDeliveryOf_WithTheExtraCostOf__For($acquisition, $cost, $currency, $reason) {
    }

    public function IReceiveTheDeliveryOf_Containing_Units($acquisition, $amount) {
    }

    public function IConsume_UnitsOf($amount, $material) {
    }

    public function IUpdateTheInventoryOf_To_Units($material, $amount) {
    }

    public function IProduce_UnitsOf($amount, $product) {
    }

    public function ISell_UnitsOf_For__To($amount, $product, $gain, $currency, $customer) {
    }

    public function IDeliver_UnitsOf_To($amount, $product, $costumer) {
    }

    public function IAddTheCostumer($costumer) {
    }

    public function ISell_UnitsOf_WithTheDocuments_Attached($amount, $product, $documents) {
    }

    public function IUpdateTheStockOf_To_Units($product, $amount) {
    }

    public function ISetWhenTo($when) {
        $this->when = new \DateTimeImmutable($when);
    }
}