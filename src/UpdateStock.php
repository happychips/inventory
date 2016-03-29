<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\ProductIdentifier;

class UpdateStock extends Command {

    /** @var ProductIdentifier */
    private $product;
    /** @var int */
    private $amount;

    /**
     * @param ProductIdentifier $product
     * @param int $amount
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(ProductIdentifier $product, $amount, \DateTimeImmutable $when = null) {
        parent::__construct($when);

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