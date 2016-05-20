<?php
namespace happy\inventory\projecting;

use happy\inventory\events\LinkedConsumptionsSet;
use happy\inventory\events\ProductRegistered;

class LinkedConsumptions {

    /** @var LinkedProductConsumptions[] */
    private $consumptions = [];

    /**
     * @return LinkedProductConsumptions[]
     */
    public function getConsumptions() {
        return array_values($this->consumptions);
    }

    public function applyProductRegistered(ProductRegistered $e) {
        $this->consumptions[(string)$e->getProduct()] = new LinkedProductConsumptions($e->getName(), $e->getUnit());
    }

    public function applyLinkedConsumptionsSet(LinkedConsumptionsSet $e) {
        $this->consumptions[(string)$e->getProduct()]->setConsumptions($e->getConsumptions());
    }
}