<?php
namespace happy\inventory\events;

use happy\inventory\model\UserIdentifier;

class MaterialRegistered extends Event {
    /** @var string */
    private $name;
    /** @var string */
    private $unit;

    /**
     * @param string $name
     * @param string $unit
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct($name, $unit, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->name = $name;
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }
}