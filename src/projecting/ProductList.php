<?php
namespace happy\inventory\projecting;

use happy\inventory\events\ProductRegistered;

class ProductList {

    private $products = [];

    public function getProducts() {
        asort($this->products);
        return $this->products;
    }

    public function applyProductRegistered(ProductRegistered $e) {
        $this->products[(string)$e->getProduct()] = $e->getName() . ' (' . $e->getUnit() . ')';
    }
}