<?php
namespace happy\inventory\model;

class MaterialAcquisition {

    /** @var MaterialIdentifier */
    private $material;
    /** @var float */
    private $amount;
    /** @var Money */
    private $cost;

    /**
     * @param MaterialIdentifier $material
     * @param float $amount
     * @param Money $cost
     */
    public function __construct(MaterialIdentifier $material, $amount, Money $cost) {
        $this->material = $material;
        $this->amount = $amount;
        $this->cost = $cost;
    }

    /**
     * @return MaterialIdentifier
     */
    public function getMaterial() {
        return $this->material;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return Money
     */
    public function getCost() {
        return $this->cost;
    }

}