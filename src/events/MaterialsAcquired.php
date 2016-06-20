<?php
namespace happy\inventory\events;

use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\MaterialAcquisition;
use happy\inventory\model\SupplierIdentifier;
use happy\inventory\model\UserIdentifier;
use rtens\domin\parameters\File;

class MaterialsAcquired extends Event {

    /** @var AcquisitionIdentifier */
    private $acquisition;
    /** @var MaterialAcquisition[] */
    private $materials;
    /** @var SupplierIdentifier|null */
    private $supplier;
    /** @var array|File[] */
    private $documents;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param MaterialAcquisition[] $materials
     * @param SupplierIdentifier|null $supplier
     * @param File[] $documents
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, array $materials,
                                SupplierIdentifier $supplier = null, array $documents,
                                UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->materials = $materials;
        $this->supplier = $supplier;
        $this->documents = $documents;
        $this->acquisition = $acquisition;
    }

    /**
     * @return MaterialAcquisition[]
     */
    public function getMaterials() {
        return $this->materials;
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