<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\ProductIdentifier;

class UpdateStock extends Command {

    /** @var ProductIdentifier */
    private $product;
    /** @var float */
    private $amount;

    /**
     * @param ProductIdentifier $product
     * @param float $amount
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
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }
}