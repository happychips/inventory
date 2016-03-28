<?php
namespace happy\inventory\events;

use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\UserIdentifier;
use rtens\domin\parameters\File;

class MaterialAcquired extends Event {
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
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(MaterialIdentifier $material, $amount, $cost, $currency, array $documents, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

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