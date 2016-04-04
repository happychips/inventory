<?php
namespace happy\inventory\events;

use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\UserIdentifier;

class CostumerDetailsChanged extends Event {

    /** @var CostumerIdentifier */
    private $costumer;
    /** @var string */
    private $contact;
    /** @var string */
    private $location;

    /**
     * CostumerContactChanged constructor.
     * @param CostumerIdentifier $costumer
     * @param string $contact
     * @param string $location
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(CostumerIdentifier $costumer, $contact, $location,
                                UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->costumer = $costumer;
        $this->contact = $contact;
        $this->location = $location;
    }

    /**
     * @return CostumerIdentifier
     */
    public function getCostumer() {
        return $this->costumer;
    }

    /**
     * @return string
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }
}