<?php
namespace happy\inventory\projecting;

use happy\inventory\events\SupplierAdded;

class SupplierList {

    private $suppliers = [];

    public function getSuppliers() {
        asort($this->suppliers);
        return $this->suppliers;
    }

    public function applySupplierAdded(SupplierAdded $e) {
        $this->suppliers[(string)$e->getSupplier()] = $e->getName();
    }
}