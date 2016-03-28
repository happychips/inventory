<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use rtens\domin\parameters\File;

class AcquireMaterial extends Command {
    /** @var MaterialIdentifier */
    private $material;
    /** @var int */
    private $amount;
    /** @var Money */
    private $cost;
    /** @var array|File[] */
    private $documents;

    /**
     * @param MaterialIdentifier $material
     * @param int $amount
     * @param Money $cost
     * @param File[] $documents
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(MaterialIdentifier $material, $amount, Money $cost, array $documents = [], \DateTimeImmutable $when = null) {
        parent::__construct($when);

        $this->material = $material;
        $this->amount = $amount;
        $this->cost = $cost;
        $this->documents = $documents;
    }

    /**
     * @return array|File[]
     */
    public function getDocuments() {
        return $this->documents;
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
}