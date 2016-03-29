<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\ProductIdentifier;

class DeliverProduct extends Command {

    /** @var ProductIdentifier */
    private $product;
    /** @var int */
    private $amount;
    /** @var CostumerIdentifier */
    private $costumer;

    /**
     * @param ProductIdentifier $product
     * @param int $amount
     * @param CostumerIdentifier $costumer
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(ProductIdentifier $product, $amount, CostumerIdentifier $costumer,
                                \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->product = $product;
        $this->amount = $amount;
        $this->costumer = $costumer;
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

    /**
     * @return CostumerIdentifier
     */
    public function getCostumer() {
        return $this->costumer;
    }
}