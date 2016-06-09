<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\AcquireMaterial;
use happy\inventory\AddCostumer;
use happy\inventory\AddSupplier;
use happy\inventory\ConsumeMaterial;
use happy\inventory\DeliverProduct;
use happy\inventory\ListAcquisitions;
use happy\inventory\ListCostumers;
use happy\inventory\ListLinkedConsumptions;
use happy\inventory\ListLinkedProductConsumptions;
use happy\inventory\ListMaterials;
use happy\inventory\ListProducts;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\ProduceProduct;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\RegisterProduct;
use happy\inventory\SetLinkedConsumption;
use happy\inventory\SetMaterialCategory;
use happy\inventory\ShowInventory;
use happy\inventory\ShowStock;
use happy\inventory\UpdateInventory;
use happy\inventory\UpdateStock;
use rtens\domin\parameters\file\MemoryFile;
use watoki\karma\testing\Specification;

class Action {

    const DEFAULT_UNIT = 'kg';

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

    public function IAcquire_UnitsOf_For_Directly($amount, $material, $cost, $currency) {
        $this->AcquireMaterial($amount, $material, $cost, $currency, null, true);
    }

    public function IAcquire_UnitsOf_From($amount, $material, $supplier) {
        $this->AcquireMaterial($amount, $material, 42, 'BTN', null, false, new SupplierIdentifier($supplier));
    }

    private function AcquireMaterial($amount, $material, $cost, $currency, $documents = null, $alreadyReceived = false, $supplier = null) {
        $this->karma->when(new AcquireMaterial(
            MaterialIdentifier::fromNameAndUnit($material, self::DEFAULT_UNIT),
            intval($amount),
            new Money($cost, $currency),
            $supplier,
            $alreadyReceived,
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

    public function IReceiveTheDeliveryOf_Partially($acquisition) {
        $this->ReceiveDelivery($acquisition, null, null, null, true);
    }

    private function ReceiveDelivery($acquisition, $amount = null, $extraCosts = null, $documents = null, $partial = false) {
        $this->karma->when(new ReceiveDelivery(
            new AcquisitionIdentifier($acquisition),
            $partial,
            $amount,
            $this->makeFiles($documents),
            $extraCosts,
            $this->when
        ));
    }

    public function IConsume_UnitsOf($amount, $material) {
        $this->karma->when(new ConsumeMaterial(
            MaterialIdentifier::fromNameAndUnit($material, self::DEFAULT_UNIT),
            $amount,
            $this->when
        ));
    }

    public function IUpdateTheInventoryOf_To_Units($material, $amount) {
        $this->karma->when(new UpdateInventory(
            MaterialIdentifier::fromNameAndUnit($material, self::DEFAULT_UNIT),
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

    public function IDeliver_UnitsOf_To($amount, $product, $costumer) {
        $this->karma->when(new DeliverProduct(
            new ProductIdentifier($product),
            $amount,
            new CostumerIdentifier($costumer),
            $this->when
        ));
    }

    public function IAddTheCostumer($costumer) {
        $this->karma->when(new AddCostumer($costumer));
    }

    public function IAddTheCostumer_WithContact_AndLocation($costumer, $contact, $location) {
        $this->karma->when(new AddCostumer($costumer, $contact, $location));
    }

    public function IAddTheSupplier($name) {
        $this->karma->when(new AddSupplier($name));
    }

    public function IUpdateTheStockOf_To_Units($product, $amount) {
        $this->karma->when(new UpdateStock(
            new ProductIdentifier($product),
            $amount,
            $this->when
        ));
    }

    public function IDateTheEventAt($when) {
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

    public function IShowTheInventory() {
        $this->karma->when(new ShowInventory());
    }

    public function IShowTheStock() {
        $this->karma->when(new ShowStock());
    }

    public function ISetTheConsumptions_For(array $consumptions, $product) {
        $this->karma->when(new SetLinkedConsumption(
            new ProductIdentifier($product),
            array_map(function ($consumption) {
                return new ConsumeMaterial(new MaterialIdentifier($consumption[1]), $consumption[0]);
            }, $consumptions)
        ));
    }

    public function IListLinkedConsumptions() {
        $this->karma->when(new ListLinkedConsumptions());
    }

    public function IListLinkedConsumptionsFor($product) {
        $this->karma->when(new ListLinkedProductConsumptions(new ProductIdentifier($product)));
    }

    public function IPut_Into($material, $category) {
        $this->karma->when(new SetMaterialCategory(MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT), $category));
    }
}