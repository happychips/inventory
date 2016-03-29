<?php
namespace happy\inventory\events;

use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\UserIdentifier;

class ProductProduced extends Event {

    /** @var ProductIdentifier */
    private $product;
    /** @var int */
    private $amount;

    /**
     * ProductProduced constructor.
     * @param ProductIdentifier $product
     * @param int $amount
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
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

}