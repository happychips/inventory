<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\AcquireMaterial;
use happy\inventory\AddCostumer;
use happy\inventory\ConsumeMaterial;
use happy\inventory\ListAcquisitions;
use happy\inventory\ListCostumers;
use happy\inventory\ListMaterials;
use happy\inventory\ListProducts;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\ProduceProduct;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\RegisterProduct;
use happy\inventory\UpdateInventory;
use happy\inventory\UpdateStock;
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
        $this->karma->when(new RegisterProduct($product, $unit, $this->when));
    }

    public function IAcquire_UnitsOf_For($amount, $material, $cost, $currency) {
        $this->AcquireMaterial($amount, $material, $cost, $currency);
    }

    public function IAcquire_UnitsOf_WithTheDocuments($amount, $material, $documents) {
        $this->AcquireMaterial($amount, $material, 42, 'FOO', $documents);
    }

    private function AcquireMaterial($amount, $material, $cost, $currency, $documents = null) {
        $this->karma->when(new AcquireMaterial(
            new MaterialIdentifier($material),
            intval($amount),
            new Money($cost, $currency),
            $this->makeFiles($documents),
            $this->when
        ));
    }

    public function IReceiveTheDeliveryOf($acquisition) {
        $this->ReceiveDelivery($acquisition);
    }

    public function IReceiveTheDeliveryOf_WithTheDocuments_Attached($acquisition, $documents) {
        $this->ReceiveDelivery($acquisition, null, [], $documents);
    }

    public function IReceiveTheDeliveryOf_WithTheExtraCostOf__For($acquisition, $cost, $currency, $reason) {
        $this->ReceiveDelivery($acquisition, null, [new ExtraCost(new Money($cost, $currency), $reason)]);
    }

    public function IReceiveTheDeliveryOf_Containing_Units($acquisition, $amount) {
        $this->ReceiveDelivery($acquisition, $amount);
    }

    private function ReceiveDelivery($acquisition, $amount = null, $extraCosts = null, $documents = null) {
        $this->karma->when(new ReceiveDelivery(
            new AcquisitionIdentifier($acquisition),
            $amount,
            $this->makeFiles($documents),
            $extraCosts,
            $this->when
        ));
    }

    public function IConsume_UnitsOf($amount, $material) {
        $this->karma->when(new ConsumeMaterial(
            new MaterialIdentifier($material),
            $amount,
            $this->when
        ));
    }

    public function IUpdateTheInventoryOf_To_Units($material, $amount) {
        $this->karma->when(new UpdateInventory(
            new MaterialIdentifier($material),
            $amount,
            $this->when
        ));
    }

    public function IProduce_UnitsOf($amount, $product) {
        $this->karma->when(new ProduceProduct(
            new ProductIdentifier($product),
            $amount,
            $this->when
        ));
    }

    public function ISell_UnitsOf_For__To($amount, $product, $gain, $currency, $customer) {
    }

    public function IDeliver_UnitsOf_To($amount, $product, $costumer) {
    }

    public function IAddTheCostumer($costumer) {
        $this->karma->when(new AddCostumer($costumer));
    }

    public function ISell_UnitsOf_WithTheDocuments_Attached($amount, $product, $documents) {
    }

    public function IUpdateTheStockOf_To_Units($product, $amount) {
        $this->karma->when(new UpdateStock(
            new ProductIdentifier($product),
            $amount,
            $this->when
        ));
    }

    public function ISetWhenTo($when) {
        $this->when = new \DateTimeImmutable($when);
    }

    /**
     * @param $documents
     * @return array
     */
    private function makeFiles($documents) {
        if (!$documents) {
            return $documents;
        }

        return array_map(function ($document) {
            return new MemoryFile($document, 'type/foo', $document . 'content');
        }, $documents);
    }

    public function IListAllMaterials() {
        $this->karma->when(new ListMaterials());
    }

    public function IListAllAcquisitions() {
        $this->karma->when(new ListAcquisitions());
    }

    public function IListAllCostumers() {
        $this->karma->when(new ListCostumers());
    }

    public function IListAllProducts() {
        $this->karma->when(new ListProducts());
    }
}