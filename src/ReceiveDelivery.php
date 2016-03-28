<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use rtens\domin\parameters\File;

class ReceiveDelivery extends Command {

    /** @var AcquisitionIdentifier */
    private $acquisition;
    /** @var File[] */
    private $documents;
    /** @var ExtraCost[] */
    private $extraCosts;
    /** @var int|null */
    private $amount;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param null|int $amount
     * @param File[] $documents
     * @param ExtraCost[] $extraCosts
     * @param \DateTimeImmutable $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, $amount = null, array $documents = [], array $extraCosts = [], \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->acquisition = $acquisition;
        $this->documents = $documents;
        $this->extraCosts = $extraCosts;
        $this->amount = $amount;
    }

    /**
     * @return int|null
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
    public function getDocuments() {
        return $this->documents;
    }

    /**
     * @return ExtraCost[]
     */
    public function getExtraCosts() {
        return $this->extraCosts;
    }
}