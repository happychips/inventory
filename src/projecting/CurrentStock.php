<?php
namespace happy\inventory\projecting;

use happy\inventory\events\ProductDelivered;
use happy\inventory\events\ProductProduced;
use happy\inventory\events\ProductRegistered;
use happy\inventory\events\StockUpdated;

class CurrentStock {

    /** @var CurrentCount[] */
    private $products = [];

    /**
     * @return CurrentCount[]
     */
    public function getProducts() {
        $products = array_values($this->products);
        usort($products, function (CurrentCount $a, CurrentCount $b) {
            return strcmp($a->getCaption(), $b->getCaption());
        });
        return $products;
    }

    public function applyProductRegistered(ProductRegistered $e) {
        $this->products[(string)$e->getProduct()] = new CurrentCount($e->getName(), $e->getUnit());
    }

    public function applyProductProduced(ProductProduced $e) {
        $this->products[(string)$e->getProduct()]->addCount($e->getAmount());
    }

    public function applyProductDelivered(ProductDelivered $e) {
        $this->products[(string)$e->getProduct()]->subtractCount($e->getAmount());
    }

    public function applyStockUpdated(StockUpdated $e) {
        $this->products[(string)$e->getProduct()]->setCount($e->getAmount());
    }
}