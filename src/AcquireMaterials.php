<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialAcquisition;
use happy\inventory\model\SupplierIdentifier;
use rtens\domin\parameters\File;
use rtens\domin\parameters\Image;

class AcquireMaterials extends Command {

    /** @var MaterialAcquisition[] */
    private $materials;
    /** @var SupplierIdentifier|null */
    private $supplier;
    /** @var bool */
    private $alreadyReceived;
    /** @var File[]|Image[]|null */
    private $documents;

    /**
     * @param MaterialAcquisition[] $materials
     * @param SupplierIdentifier $supplier
     * @param bool $alreadyReceived
     * @param File[]|Image[]|null $documents
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(array $materials, SupplierIdentifier $supplier,
                                $alreadyReceived = true, array $documents = null, \DateTimeImmutable $when = null) {
        parent::__construct($when);

        $this->materials = $materials;
        $this->supplier = $supplier;
        $this->alreadyReceived = $alreadyReceived;
        $this->documents = $documents ?: [];
    }

    /**
     * @return File[]
     */
    public function getDocumentFiles() {
        if (!$this->documents) {
            return [];
        }
        return array_map(function ($fileOrImage) {
            if ($fileOrImage instanceof Image) {
                return $fileOrImage->getFile();
            }
            return $fileOrImage;
        }, $this->documents);
    }

    /**
     * @return boolean
     */
    public function isAlreadyReceived() {
        return $this->alreadyReceived;
    }

    /**
     * @return SupplierIdentifier
     */
    public function getSupplier() {
        return $this->supplier;
    }

    /**
     * @return MaterialAcquisition[]
     */
    public function getMaterials() {
        return $this->materials;
    }
}