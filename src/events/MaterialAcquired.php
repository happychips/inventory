<?php
namespace happy\inventory\events;

use happy\inventory\model\MaterialIdentifier;
use rtens\domin\parameters\File;

class MaterialAcquired {
    /** @var MaterialIdentifier */
    private $material;
    /** @var int */
    private $amount;
    /** @var int */
    private $cost;
    /** @var string */
    private $currency;
    /** @var array|File[] */
    private $documents;

    /**
     * @param MaterialIdentifier $material
     * @param int $amount
     * @param int $cost
     * @param string $currency
     * @param File[] $documents
     */
    public function __construct(MaterialIdentifier $material, $amount, $cost, $currency, array $documents) {
        $this->material = $material;
        $this->amount = $amount;
        $this->cost = $cost;
        $this->currency = $currency;
        $this->documents = $documents;
    }

    /**
     * @return MaterialIdentifier
     */
    public function getMaterial() {
        return $this->material;
    }

    /**
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @return File[]
     */
    public function getDocuments() {
        return $this->documents;
    }
}