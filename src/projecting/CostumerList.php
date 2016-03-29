<?php
namespace happy\inventory\projecting;

use happy\inventory\events\CostumerAdded;

class CostumerList {

    private $costumers = [];

    public function getCostumers() {
        asort($this->costumers);
        return $this->costumers;
    }

    public function applyCostumerAdded(CostumerAdded $e) {
        $this->costumers[(string)$e->getCostumer()] = $e->getName();
    }
}