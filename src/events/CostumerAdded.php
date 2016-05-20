<?php
namespace happy\inventory\events;

use happy\inventory\model\CostumerIdentifier;
use happy\inventory\model\UserIdentifier;

class CostumerAdded extends Event {

    /** @var CostumerIdentifier */
    private $costumer;
    /** @var string */
    private $name;

    /**
     * @param CostumerIdentifier $costumer
     * @param string $name
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(CostumerIdentifier $costumer, $name, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->costumer = $costumer;
        $this->name = $name;
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
    public function getName() {
        return $this->name;
    }
}