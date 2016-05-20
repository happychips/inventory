<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\ProductIdentifier;

class SetLinkedConsumption extends Command {

    /** @var ProductIdentifier */
    private $product;
    /** @var array|ConsumeMaterial[] */
    private $consumptions;

    /**
     * @param ProductIdentifier $product
     * @param ConsumeMaterial[] $consumptions
     * @param null|\DateTimeImmutable $when
     */
    public function __construct(ProductIdentifier $product, array $consumptions, \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->product = $product;
        $this->consumptions = $consumptions;
    }

    /**
     * @return ProductIdentifier
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @return array|ConsumeMaterial[]
     */
    public function getConsumptions() {
        return $this->consumptions;
    }
}