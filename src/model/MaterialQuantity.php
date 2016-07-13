<?php
namespace happy\inventory\model;

class MaterialQuantity {

    /** @var MaterialIdentifier */
    private $material;
    /** @var float */
    private $amount;

    /**
     * @param MaterialIdentifier $material
     * @param float $amount
     */
    public function __construct(MaterialIdentifier $material, $amount) {
        $this->material = $material;
        $this->amount = $amount;
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
}