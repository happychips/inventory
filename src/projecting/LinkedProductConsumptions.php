<?php
namespace happy\inventory\projecting;

use happy\inventory\ConsumeMaterial;

class LinkedProductConsumptions {

    /** @var string */
    private $product;
    /** @var string */
    private $unit;
    /** @var ConsumeMaterial[] */
    private $consumptions = [];

    /**
     * @param string $product
     * @param string $unit
     */
    public function __construct($product, $unit) {
        $this->product = $product;
        $this->unit = $unit;
    }

    public function getConsumptions() {
        return $this->consumptions;
    }

    /**
     * @param ConsumeMaterial[] $consumptions
     */
    public function setConsumptions($consumptions) {
        $this->consumptions = $consumptions;
    }
}