<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialIdentifier;

class UpdateInventory extends Command {

    /** @var MaterialIdentifier */
    private $material;
    /** @var int */
    private $amount;

    /**
     * @param MaterialIdentifier $material
     * @param int $amount
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(MaterialIdentifier $material, $amount, \DateTimeImmutable $when = null) {
        parent::__construct($when);
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