<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\CostumerAdded;
use happy\inventory\events\CostumerDetailsChanged;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\Event;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\ProductDelivered;
use happy\inventory\events\ProductProduced;
use happy\inventory\events\ProductRegistered;
use happy\inventory\events\StockUpdated;
use happy\inventory\events\SupplierAdded;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\model\UserIdentifier;
use happy\inventory\projecting\AcquisitionList;
use happy\inventory\projecting\CostumerList;
use happy\inventory\projecting\CurrentInventory;
use happy\inventory\projecting\CurrentStock;
use happy\inventory\projecting\MaterialList;
use happy\inventory\projecting\ProductList;
use rtens\domin\parameters\File;
use rtens\domin\parameters\file\MemoryFile;
use rtens\scrut\fixtures\ExceptionFixture;
use watoki\karma\testing\Specification;

class Outcome {

    /** @var Specification */
    private $karma;
    /** @var ExceptionFixture */
    private $try;

    /**
     * @param Specification $karma
     * @param ExceptionFixture $try
     */
    public function __construct(Specification $karma, ExceptionFixture $try) {
        $this->karma = $karma;
        $this->try = $try;
    }

    private function then($eventClass, $condition = null, $count = null) {
        $metExpectation = $this->karma->then->shouldMatchAllObject($eventClass, $condition);
        if ($count) {
            $metExpectation->count($count);
        }
        $this->try->thenNoExceptionShouldBeThrown();
    }

    private function thenReturn(callable $condition) {
        $this->karma->then->returnShouldMatchAll($condition);
        $this->try->thenNoExceptionShouldBeThrown();
    }

    public function ItShouldFailWith($message) {
        $this->try->thenTheException_ShouldBeThrown($message);
    }

    public function AMaterial_WithTheUnit_ShouldBeRegistered($material, $unit) {
        $this->then(MaterialRegistered::class, function (MaterialRegistered $e) use ($material, $unit) {
            return [
                [$e->getName(), $material],
                [$e->getUnit(), $unit]
            ];
        });
    }

    public function AProduct_WithTheUnit_ShouldBeRegistered($article, $unit) {
        $this->then(ProductRegistered::class, function (ProductRegistered $e) use ($article, $unit) {
            return [
                [$e->getName(), $article],
                [$e->getUnit(), $unit]
            ];
        });
    }

    public function AllEventsShouldHaveHappenedAt($when) {
        $this->then(Event::class, function (Event $e) use ($when) {
            return [
                [$e->getWhen()->format('c'), (new \DateTimeImmutable($when))->format('c')]
            ];
        }, count($this->karma->appendedEvents()));
    }

    public function AllEventsShouldBeCausedBy($user) {
        $this->then(Event::class, function (Event $e) use ($user) {
            return [
                [$e->getWho(), new UserIdentifier($user)]
            ];
        }, count($this->karma->appendedEvents()));
    }

    public function _UnitsOf_For__ShouldBeAcquired($amount, $material, $cost, $currency) {
        $this->then(MaterialAcquired::class, function (MaterialAcquired $e) use ($amount, $material, $cost, $currency) {
            return [
                [$e->getMaterial(), MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT)],
                [$e->getAmount(), intval($amount)],
                [$e->getCost(), new Money($cost, $currency)],
            ];
        });
    }

    public function _UnitsOf_ShouldBeAcquiredFrom($amount, $material, $supplier) {
        $this->then(MaterialAcquired::class, function (MaterialAcquired $e) use ($amount, $material, $supplier) {
            return [
                [$e->getMaterial(), MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT)],
                [$e->getAmount(), intval($amount)],
                [$e->getSupplier(), new SupplierIdentifier($supplier)],
            ];
        });
    }

    public function TheAcquisitionShouldContainTheDocuments($documents) {
        $this->then(MaterialAcquired::class, function (MaterialAcquired $e) use ($documents) {
            $conditions = [];
            foreach ($documents as $i => $name) {
                $conditions[] = [$e->getDocuments()[$i], new MemoryFile($name, 'type/foo', $name . 'content')];
            }
            return $conditions;
        });
    }

    public function _ShouldBeReceived($acquisition) {
        $this->then(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)]
            ];
        });
    }

    public function _ShouldBePartiallyReceived($acquisition) {
        $this->then(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)],
                [$e->isPartialDelivery(), true]
            ];
        });
    }

    public function _ShouldBeReceivedWithTheDocuments_Attached($acquisition, $documents) {
        $this->then(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $documents) {
            $conditions = [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)]
            ];
            foreach ($documents as $i => $name) {
                $conditions[] = [$e->getDocuments()[$i], new MemoryFile($name, 'type/foo', $name . 'content')];
            }
            return $conditions;
        });
    }

    public function _ShouldBeReceivedWithTheExtraCostOf__For($acquisition, $cost, $currency, $reason) {
        $this->then(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $cost, $currency, $reason) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)],
                [$e->getExtraCosts(), [new ExtraCost(new Money($cost, $currency), $reason)]]
            ];
        });
    }

    public function _ShouldBeReceivedContaining_Units($acquisition, $amount) {
        $this->then(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $amount) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function _UnitsOf_ShouldBeConsumed($amount, $material) {
        $this->then(MaterialConsumed::class, function (MaterialConsumed $e) use ($amount, $material) {
            return [
                [$e->getMaterial(), MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function TheInventoryOf_ShouldBeUpdatedTo_Units($material, $amount) {
        $this->then(InventoryUpdated::class, function (InventoryUpdated $e) use ($amount, $material) {
            return [
                [$e->getMaterial(), MaterialIdentifier::fromNameAndUnit($material, Action::DEFAULT_UNIT)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function _UnitsOf_ShouldBeProduced($amount, $product) {
        $this->then(ProductProduced::class, function (ProductProduced $e) use ($amount, $product) {
            return [
                [$e->getProduct(), new ProductIdentifier($product)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function _UnitsOf_ShouldBeDeliveredTo($amount, $product, $costumer) {
        $this->then(ProductDelivered::class, function (ProductDelivered $e) use ($amount, $product, $costumer) {
            return [
                [$e->getProduct(), new ProductIdentifier($product)],
                [$e->getAmount(), $amount],
                [$e->getCostumer(), new CostumerIdentifier($costumer)]
            ];
        });
    }

    public function TheCostumer_ShouldBeAdded($costumer) {
        $this->then(CostumerAdded::class, function (CostumerAdded $e) use ($costumer) {
            return [
                [$e->getName(), $costumer]
            ];
        });
    }

    public function _UnitsOf_ShouldBeSoldWithTheDocuments_Attached($amount, $product, $document) {
    }

    public function TheStockOf_ShouldBeUpdatedTo_Units($product, $amount) {
        $this->then(StockUpdated::class, function (StockUpdated $e) use ($product, $amount) {
            return [
                [$e->getProduct(), new ProductIdentifier($product)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function ItSholdList_Materials($int) {
        $this->thenReturn(function (MaterialList $returned) use ($int) {
            return [
                'count' => [count($returned->getMaterials()), $int]
            ];
        });
    }

    public function Material_ShouldHaveTheCaption($pos, $caption) {
        $this->thenReturn(function (MaterialList $returned) use ($pos, $caption) {
            return [
                [$returned->getMaterials()[$pos], $caption]
            ];
        });
    }

    public function Material_ShouldBe($pos, $material) {
        $this->thenReturn(function (MaterialList $returned) use ($pos, $material) {
            return [
                [array_keys($returned->getMaterials())[$pos - 1], $material]
            ];
        });
    }

    public function ItShouldList_Acquisitions($int) {
        $this->thenReturn(function (AcquisitionList $returned) use ($int) {
            return [
                'count' => [count($returned->getAcquisitions()), $int]
            ];
        });
    }

    public function Acquisition_ShouldHaveTheCaption($id, $caption) {
        $this->thenReturn(function (AcquisitionList $returned) use ($id, $caption) {
            return [
                [$returned->getAcquisitions()[$id], $caption]
            ];
        });
    }

    public function Acquisition_ShouldBe($pos, $id) {
        $this->thenReturn(function (AcquisitionList $returned) use ($pos, $id) {
            return [
                [array_keys($returned->getAcquisitions())[$pos - 1], $id]
            ];
        });
    }

    public function ThereShouldBe_Costumers($int) {
        $this->thenReturn(function (CostumerList $returned) use ($int) {
            return [
                'count' => [count($returned->getCostumers()), $int]
            ];
        });
    }

    public function Costumer_ShouldBe($pos, $id) {
        $this->thenReturn(function (CostumerList $returned) use ($pos, $id) {
            return [
                [array_keys($returned->getCostumers())[$pos - 1], $id]
            ];
        });
    }

    public function ItShouldList_Products($int) {
        $this->thenReturn(function (ProductList $returned) use ($int) {
            return [
                'count' => [count($returned->getProducts()), $int]
            ];
        });
    }

    public function Product_ShouldHaveTheCaption($id, $caption) {
        $this->thenReturn(function (ProductList $returned) use ($id, $caption) {
            return [
                [$returned->getProducts()[$id], $caption]
            ];
        });
    }

    public function Product_ShouldBe($pos, $id) {
        $this->thenReturn(function (ProductList $returned) use ($pos, $id) {
            return [
                [array_keys($returned->getProducts())[$pos - 1], $id]
            ];
        });
    }

    public function TheSupplier_ShouldBeAdded($name) {
        $this->then(SupplierAdded::class, function (SupplierAdded $e) use ($name) {
            return [
                [$e->getName(), $name]
            ];
        });
    }

    public function TheContactOfCostumer_ShouldBeChangedTo($costumer, $contact) {
        $this->then(CostumerDetailsChanged::class, function (CostumerDetailsChanged $e) use ($costumer, $contact) {
            return [
                [$e->getCostumer(), new CostumerIdentifier($costumer)],
                [$e->getContact(), $contact]
            ];
        });
    }

    public function TheLocationOfCostumer_ShouldBeChangedTo($costumer, $location) {
        $this->then(CostumerDetailsChanged::class, function (CostumerDetailsChanged $e) use ($costumer, $location) {
            return [
                [$e->getCostumer(), new CostumerIdentifier($costumer)],
                [$e->getLocation(), $location]
            ];
        });
    }

    public function TheInventoryShouldContain_Materials($int) {
        $this->thenReturn(function (CurrentInventory $inventory) use ($int) {
            return [
                'total' => [count($inventory->getMaterials()), $int]
            ];
        });
    }

    public function MaterialOfTheInventory_ShouldHaveTheCaption($int, $caption) {
        $this->thenReturn(function (CurrentInventory $inventory) use ($int, $caption) {
            return [
                'caption' => [$inventory->getMaterials()[$int - 1]->getCaption(), $caption]
            ];
        });
    }

    public function MaterialOfTheInventory_ShouldHaveTheCount($int, $count) {
        $this->thenReturn(function (CurrentInventory $inventory) use ($int, $count) {
            return [
                'count' => [$inventory->getMaterials()[$int - 1]->getCount(), $count]
            ];
        });
    }

    public function TheStockShouldContain_Products($int) {
        $this->thenReturn(function (CurrentStock $stock) use ($int) {
            return [
                'total' => [count($stock->getProducts()), $int]
            ];
        });
    }

    public function Product_InStockShouldHaveTheCaption($int, $caption) {
        $this->thenReturn(function (CurrentStock $stock) use ($int, $caption) {
            return [
                'caption' => [$stock->getProducts()[$int - 1]->getCaption(), $caption]
            ];
        });
    }

    public function Product_InStockShouldCount($int, $count) {
        $this->thenReturn(function (CurrentStock $stock) use ($int, $count) {
            return [
                'count' => [$stock->getProducts()[$int - 1]->getCount(), $count]
            ];
        });
    }
}