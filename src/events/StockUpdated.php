<?php
namespace happy\inventory\events;

use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\UserIdentifier;

class StockUpdated extends Event {

    /** @var ProductIdentifier */
    private $product;
    /** @var float */
    private $amount;

    /**
     * @param ProductIdentifier $product
     * @param float $amount
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(ProductIdentifier $product, $amount, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->product = $product;
        $this->amount = $amount;
    }

    /**
     * @return ProductIdentifier
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }
}