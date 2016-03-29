<?php
namespace happy\inventory\events;

use happy\inventory\model\ProductIdentifier;
use happy\inventory\model\UserIdentifier;

class ProductRegistered extends Event {

    /** @var ProductIdentifier */
    private $product;
    /** @var string */
    private $name;
    /** @var string */
    private $unit;

    /**
     * @param ProductIdentifier $product
     * @param string $name
     * @param string $unit
     * @param UserIdentifier $who
     * @param \DateTimeImmutable $when
     */
    public function __construct(ProductIdentifier $product, $name, $unit, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->product = $product;
        $this->name = $name;
        $this->unit = $unit;
    }

    /**
     * @return ProductIdentifier
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }
}