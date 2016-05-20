<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialIdentifier;

class ConsumeMaterial extends Command {

    /** @var MaterialIdentifier */
    private $material;
    /** @var float */
    private $amount;

    /**
     * @param MaterialIdentifier $material
     * @param float $amount
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
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }
}