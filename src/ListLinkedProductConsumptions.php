<?php
namespace happy\inventory;

use happy\inventory\model\ProductIdentifier;

class ListLinkedProductConsumptions {

    /** @var ProductIdentifier */
    private $product;

    /**
     * @param ProductIdentifier $product
     */
    public function __construct(ProductIdentifier $product) {
        $this->product = $product;
    }

    /**
     * @return ProductIdentifier
     */
    public function getProduct() {
        return $this->product;
    }
}