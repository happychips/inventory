<?php
namespace happy\inventory\events;

use happy\inventory\model\AcquisitionIdentifier;
use happy\inventory\model\DeviantAmount;
use happy\inventory\model\ExtraCost;
use happy\inventory\model\MaterialIdentifier;
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
    /** @var DeviantAmount[] */
    private $deviantAmounts;

    /**
     * @param AcquisitionIdentifier $acquisition
     * @param bool $partialDelivery
     * @param DeviantAmount[] $deviantAmounts
     * @param File[] $documents
     * @param ExtraCost[] $extraCosts
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(AcquisitionIdentifier $acquisition, $partialDelivery, array $deviantAmounts,
                                array $documents, array $extraCosts, UserIdentifier $who,
                                \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->acquisition = $acquisition;
        $this->partialDelivery = $partialDelivery;
        $this->documents = $documents;
        $this->extraCosts = $extraCosts;
        $this->deviantAmounts = $deviantAmounts;
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
     * @return boolean
     */
    public function isPartialDelivery() {
        return $this->partialDelivery;
    }

    public function hasDeviantAmount(MaterialIdentifier $material) {
        return $this->getDeviantAmount($material) !== null;
    }

    public function getDeviantAmount(MaterialIdentifier $material) {
        if ($this->amount) {
            return $this->amount;
        }

        foreach ($this->deviantAmounts as $amount) {
            if ($amount->getMaterial() == $material) {
                return $amount->getAmount();
            }
        }

        return null;
    }
}