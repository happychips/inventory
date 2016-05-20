<?php
namespace happy\inventory\events;

use happy\inventory\ConsumeMaterial;
use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\UserIdentifier;

class LinkedConsumptionsSet extends Event {

    /** @var ProductIdentifier */
    private $product;
    /** @var ConsumeMaterial[] */
    private $consumptions;

    /**
     * @param ProductIdentifier $product
     * @param ConsumeMaterial[] $consumptions
     * @param UserIdentifier $who
     * @param null|\DateTimeImmutable $when
     */
    public function __construct(ProductIdentifier $product, array $consumptions, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->product = $product;
        $this->consumptions = $consumptions;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getConsumptions() {
        return $this->consumptions;
    }
}