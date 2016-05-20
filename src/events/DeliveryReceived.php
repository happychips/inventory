<?php
namespace happy\inventory\events;

use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\UserIdentifier;
use rtens\domin\parameters\File;

class DeliveryReceived extends Event {

    /** @var AcquisitionIdentifier */
    private $acquisition;
    /** @var bool */
    private $partialDelivery;
    /** @var File[] */
    private $documents;
    /** @var ExtraCost[] */
    private $extraCosts;
    /** @var float|null */
    private $amount;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param bool $partialDelivery
     * @param float|null $amount
     * @param File[] $documents
     * @param ExtraCost[] $extraCosts
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, $partialDelivery, $amount, array $documents,
                                array $extraCosts, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->acquisition = $acquisition;
        $this->partialDelivery = $partialDelivery;
        $this->documents = $documents;
        $this->extraCosts = $extraCosts;
        $this->amount = $amount;
    }

    /**
     * @return array|ExtraCost[]
     */
    public function getExtraCosts() {
        return $this->extraCosts;
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
     * @return float|null
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return boolean
     */
    public function isPartialDelivery() {
        return $this->partialDelivery;
    }
}