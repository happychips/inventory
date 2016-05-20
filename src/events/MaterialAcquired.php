<?php
namespace happy\inventory\events;

use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\Money;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\model\UserIdentifier;
use rtens\domin\parameters\File;

class MaterialAcquired extends Event {

    /** @var AcquisitionIdentifier */
    private $acquisition;
    /** @var MaterialIdentifier */
    private $material;
    /** @var float */
    private $amount;
    /** @var SupplierIdentifier|null */
    private $supplier;
    /** @var Money */
    private $cost;
    /** @var array|File[] */
    private $documents;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param MaterialIdentifier $material
     * @param float $amount
     * @param Money $cost
     * @param SupplierIdentifier|null $supplier
     * @param File[] $documents
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, MaterialIdentifier $material, $amount,
                                Money $cost, SupplierIdentifier $supplier = null, array $documents,
                                UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->material = $material;
        $this->amount = $amount;
        $this->cost = $cost;
        $this->supplier = $supplier;
        $this->documents = $documents;
        $this->acquisition = $acquisition;
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

    /**
     * @return File[]
     */
    public function getDocuments() {
        return $this->documents;
    }

    /**
     * @return AcquisitionIdentifier
     */
    public function getAcquisition() {
        return $this->acquisition;
    }

    /**
     * @return SupplierIdentifier|null
     */
    public function getSupplier() {
        return $this->supplier;
    }
}