<?php
namespace happy\inventory\projecting;

use happy\inventory\ConsumeMaterial;
use happy\inventory\events\LinkedConsumptionsSet;
use happy\inventory\events\ProductRegistered;
use happy\inventory\model\ProductIdentifier;

class LinkedProductConsumptions {

    /** @var ProductIdentifier */
    private $product;
    /** @var string */
    private $productName;
    /** @var string */
    private $unit;
    /** @var ConsumeMaterial[] */
    private $consumptions = [];

    /**
     * @param ProductIdentifier $product
     */
    public function __construct(ProductIdentifier $product) {
        $this->product = $product;
    }

    public function applyProductRegistered(ProductRegistered $e) {
        if ($e->getProduct() == $this->product) {
            $this->productName = $e->getName();
            $this->unit = $e->getUnit();
        }
    }

    public function applyLinkedConsumptionsSet(LinkedConsumptionsSet $e) {
        if ($e->getProduct() == $this->product) {
            $this->consumptions = $e->getConsumptions();
        }
    }

    /**
     * @return string
     */
    public function getProduct() {
        return "{$this->productName} ({$this->unit})";
    }

    /**
     * @return \happy\inventory\ConsumeMaterial[]
     */
    public function getConsumptions() {
        return $this->consumptions;
    }

    /**
     * @param ConsumeMaterial[] $consumptions
     */
    public function setConsumptions($consumptions) {
        $this->consumptions = $consumptions;
    }

    /**
     * @return ProductIdentifier
     */
    public function productIdentifier() {
        return $this->product;
    }
}