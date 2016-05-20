<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use rtens\domin\parameters\File;
use rtens\domin\parameters\Image;

class ReceiveDelivery extends Command {

    /** @var AcquisitionIdentifier */
    private $acquisition;
    /** @var bool */
    private $partialDelivery;
    /** @var File[]|Image[]|null */
    private $documents;
    /** @var ExtraCost[]|null */
    private $extraCosts;
    /** @var float|null */
    private $amount;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param bool $partialDelivery
     * @param null|float $amount
     * @param File[]|Image[]|null $documents
     * @param ExtraCost[]|null $extraCosts
     * @param \DateTimeImmutable $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, $partialDelivery = false, $amount = null,
                                array $documents = null, array $extraCosts = null, \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->acquisition = $acquisition;
        $this->partialDelivery = $partialDelivery;
        $this->documents = $documents ?: [];
        $this->extraCosts = $extraCosts ?: [];
        $this->amount = $amount;
    }

    /**
     * @return float|null
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return AcquisitionIdentifier
     */
    public function getAcquisition() {
        return $this->acquisition;
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
     * @return ExtraCost[]|null
     */
    public function getExtraCosts() {
        return $this->extraCosts;
    }

    /**
     * @return boolean
     */
    public function isPartialDelivery() {
        return $this->partialDelivery;
    }
}