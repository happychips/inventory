<?php
namespace happy\inventory\events;

use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\UserIdentifier;
use rtens\domin\parameters\File;

class MaterialAcquired extends Event {
    /** @var AcquisitionIdentifier */
    private $identifier;
    /** @var MaterialIdentifier */
    private $material;
    /** @var int */
    private $amount;
    /** @var Money */
    private $cost;
    /** @var array|File[] */
    private $documents;

    /**
     * @param AcquisitionIdentifier $identifier
     * @param MaterialIdentifier $material
     * @param int $amount
     * @param Money $cost
     * @param File[] $documents
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(AcquisitionIdentifier $identifier, MaterialIdentifier $material, $amount,
                                Money $cost, array $documents, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->material = $material;
        $this->amount = $amount;
        $this->cost = $cost;
        $this->documents = $documents;
        $this->identifier = $identifier;
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
     * @return Money
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * @return File[]
     */
    public function getDocuments() {
        return $this->documents;
    }

    /**
     * @return AcquisitionIdentifier
     */
    public function getIdentifier() {
        return $this->identifier;
    }
}