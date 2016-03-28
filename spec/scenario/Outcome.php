<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\events\MaterialRegistered;
use rtens\scrut\fixtures\ExceptionFixture;
use watoki\karma\Specification;

class Outcome {

    /** @var Specification */
    private $karma;
    /** @var ExceptionFixture */
    private $try;

    /**
     * @param Specification $karma
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
        $this->try->thenNoExceptionShouldBeThrown();
    }

    public function AProduct_WithTheUnit_ShouldBeRegistered($article, $unit) {
    }

    public function AllEventsShouldHaveHappenedAt($when) {
    }

    public function AllEventsShouldBeCausedBy($user) {
    }

    public function _UnitsOf_For__ShouldBeAcquired($amount, $material, $cost, $currency) {
    }

    public function TheAcquisitionShouldContainTheDocuments($documents) {
    }

    public function _ShouldBeReceived($acquisition) {
    }

    public function _ShouldBeReceivedWithTheDocuments_Attached($acquisition, $documents) {
    }

    public function _ShouldBeReceivedWithTheExtraCostOf__For($acquisition, $cost, $currency, $reason) {
    }

    public function _ShouldBeReceivedContaining_Units($acquisition, $amount) {
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
}