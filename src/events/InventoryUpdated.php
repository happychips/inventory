<?php
namespace happy\inventory\events;

use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\UserIdentifier;

class InventoryUpdated extends Event {

    /** @var MaterialIdentifier */
    private $material;
    /** @var int */
    private $amount;

    /**
     * @param MaterialIdentifier $material
     * @param int $amount
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(MaterialIdentifier $material, $amount, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);

        $this->material = $material;
        $this->amount = $amount;
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

}