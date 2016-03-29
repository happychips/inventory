<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\Event;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\UserIdentifier;
use happy\inventory\projecting\AcquisitionList;
use happy\inventory\projecting\MaterialList;
use rtens\domin\parameters\File;
use rtens\domin\parameters\file\MemoryFile;
use rtens\scrut\fixtures\ExceptionFixture;
use watoki\karma\Specification;

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
        $this->karma->thenShould($eventClass, $condition, $count);
        $this->try->thenNoExceptionShouldBeThrown();
    }

    private function thenReturn(callable $condition) {
        $this->karma->thenItShouldReturn($condition);
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
    }

    public function AllEventsShouldHaveHappenedAt($when) {
        $this->then(Event::class, function (Event $e) use ($when) {
            return [
                [$e->getWhen()->format('c'), (new \DateTimeImmutable($when))->format('c')]
            ];
        }, count($this->karma->allEvents()));
    }

    public function AllEventsShouldBeCausedBy($user) {
        $this->then(Event::class, function (Event $e) use ($user) {
            return [
                [$e->getWho(), new UserIdentifier($user)]
            ];
        }, count($this->karma->allEvents()));
    }

    public function _UnitsOf_For__ShouldBeAcquired($amount, $material, $cost, $currency) {
        $this->then(MaterialAcquired::class, function (MaterialAcquired $e) use ($amount, $material, $cost, $currency) {
            return [
                [$e->getMaterial(), new MaterialIdentifier($material)],
                [$e->getAmount(), intval($amount)],
                [$e->getCost(), new Money($cost, $currency)],
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
                [$e->getMaterial(), new MaterialIdentifier($material)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function TheInventoryOf_ShouldBeUpdatedTo_Units($material, $amount) {
        $this->then(InventoryUpdated::class, function (InventoryUpdated $e) use ($amount, $material) {
            return [
                [$e->getMaterial(), new MaterialIdentifier($material)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function _UnitsOf_ShouldBeProduced($amount, $prodcut) {
    }

    public function _UnitsOf_ShouldBeSoldFor__To($amount, $product, $gain, $currency, $costumer) {
    }

    public function _UnitsOf_ShouldBeDeliveredTo($amount, $product, $costumer) {
    }

    public function TheCostumer_ShouldBeAdded($costumer) {
    }

    public function _UnitsOf_ShouldBeSoldWithTheDocuments_Attached($amount, $product, $document) {
    }

    public function TheStockOf_ShouldBeUpdatedTo_Units($product, $amount) {
    }

    public function ItSholdList_Materials($int) {
        $this->thenReturn(function ($returned) use ($int) {
            if (!($returned instanceof MaterialList)) {
                return false;
            }

            return [
                'count' => [count($returned->getMaterials()), $int]
            ];
        });
    }

    public function Material_ShouldHaveTheCaption($pos, $caption) {
        $this->thenReturn(function ($returned) use ($pos, $caption) {
            if (!($returned instanceof MaterialList)) {
                return false;
            }

            return [
                [$returned->getMaterials()[$pos], $caption]
            ];
        });
    }

    public function Material_ShouldBe($pos, $material) {
        $this->thenReturn(function ($returned) use ($pos, $material) {
            if (!($returned instanceof MaterialList)) {
                return false;
            }

            return [
                [array_keys($returned->getMaterials())[$pos-1], $material]
            ];
        });
    }

    public function ItShouldList_Acquisitions($int) {
        $this->thenReturn(function ($returned) use ($int) {
            if (!($returned instanceof AcquisitionList)) {
                return false;
            }

            return [
                'count' => [count($returned->getAcquisitions()), $int]
            ];
        });
    }

    public function Acqusition_ShouldHaveTheCaption($id, $caption) {
        $this->thenReturn(function ($returned) use ($id, $caption) {
            if (!($returned instanceof AcquisitionList)) {
                return false;
            }

            return [
                [$returned->getAcquisitions()[$id], $caption]
            ];
        });
    }

    public function Acqusition_ShouldBe($pos, $id) {
        $this->thenReturn(function ($returned) use ($pos, $id) {
            if (!($returned instanceof AcquisitionList)) {
                return false;
            }

            return [
                [array_keys($returned->getAcquisitions())[$pos-1], $id]
            ];
        });
    }

    public function ThereShouldBe_Costumers($count) {
    }

    public function Costumer_ShouldBe($pos, $name) {
    }

    public function ItShouldList_Products($int) {
    }

    public function Product_ShouldHaveTheCaption($id, $caption) {
    }

    public function Product_ShouldBe($pos, $id) {
    }
}