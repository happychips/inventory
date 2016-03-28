<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\Event;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\UserIdentifier;
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

    public function ItShouldFailWith($message) {
        $this->try->thenTheException_ShouldBeThrown($message);
    }

    public function AMaterial_WithTheUnit_ShouldBeRegistered($material, $unit) {
        $this->karma->thenShould(MaterialRegistered::class, function (MaterialRegistered $e) use ($material, $unit) {
            return [
                [$e->getName(), $material],
                [$e->getUnit(), $unit]
            ];
        });
        $this->pass();
    }

    public function AProduct_WithTheUnit_ShouldBeRegistered($article, $unit) {
    }

    public function AllEventsShouldHaveHappenedAt($when) {
        $this->karma->thenShould(Event::class, function (Event $e) use ($when) {
            return [
                [$e->getWhen()->format('c'), (new \DateTimeImmutable($when))->format('c')]
            ];
        }, count($this->karma->allEvents()));
        $this->pass();
    }

    public function AllEventsShouldBeCausedBy($user) {
        $this->karma->thenShould(Event::class, function (Event $e) use ($user) {
            return [
                [$e->getWho(), new UserIdentifier($user)]
            ];
        }, count($this->karma->allEvents()));
        $this->pass();
    }

    public function _UnitsOf_For__ShouldBeAcquired($amount, $material, $cost, $currency) {
        $this->karma->thenShould(MaterialAcquired::class, function (MaterialAcquired $e) use ($amount, $material, $cost, $currency) {
            return [
                [$e->getMaterial(), new MaterialIdentifier($material)],
                [$e->getAmount(), intval($amount)],
                [$e->getCost(), new Money($cost, $currency)],
            ];
        });
        $this->pass();
    }

    public function TheAcquisitionShouldContainTheDocuments($documents) {
        $this->karma->thenShould(MaterialAcquired::class, function (MaterialAcquired $e) use ($documents) {
            $conditions = [];
            foreach ($documents as $i => $name) {
                $conditions[] = [$e->getDocuments()[$i], new MemoryFile($name, 'type/foo', $name . 'content')];
            }
            return $conditions;
        });
        $this->pass();
    }

    public function _ShouldBeReceived($acquisition) {
        $this->karma->thenShould(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)]
            ];
        });
    }

    public function _ShouldBeReceivedWithTheDocuments_Attached($acquisition, $documents) {
        $this->karma->thenShould(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $documents) {
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
        $this->karma->thenShould(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $cost, $currency, $reason) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)],
                [$e->getExtraCosts(), [new ExtraCost(new Money($cost, $currency), $reason)]]
            ];
        });
    }

    public function _ShouldBeReceivedContaining_Units($acquisition, $amount) {
        $this->karma->thenShould(DeliveryReceived::class, function (DeliveryReceived $e) use ($acquisition, $amount) {
            return [
                [$e->getAcquisition(), new AcquisitionIdentifier($acquisition)],
                [$e->getAmount(), $amount]
            ];
        });
    }

    public function _UnitsOf_ShouldBeConsumed($amount, $material) {
    }

    public function TheInventoryOf_ShouldBeUpdatedTo_Units($material, $amount) {
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

    private function pass() {
        $this->try->thenNoExceptionShouldBeThrown();
    }
}